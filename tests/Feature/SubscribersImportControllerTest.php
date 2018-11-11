<?php

namespace Tests\Feature;

use Storage;

use App\Models\Segment;
use App\Models\Subscriber;
use App\User;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Testing\File;

class SubscribersImportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        // empty import folder
        Storage::disk('local')->deleteDirectory('imports');
    }

    protected function createUser()
    {
        return factory(User::class)->create();
    }

    /** @test */
    function it_should_forbid_the_access_to_the_show_endpoint_if_unauthenticated()
    {
        $response = $this->get(route('subscribers.import'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    function it_should_forbid_the_access_to_the_store_endpoint_if_unauthenticated()
    {
        $response = $this->post(route('subscribers.import.store'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    function it_should_show_the_list_of_segments_in_the_upload_form()
    {
        $user = $this->createUser();

        $segment = factory(Segment::class)->create(['name' => 'First']);

        $response = $this->actingAs($user, 'web')
            ->get(route('subscribers.import'));

        $response->assertStatus(200);
        $response->assertViewHas('segments', [
            $segment->id => $segment->name
        ]);
    }

    /** @test */
    function it_should_upload_the_list_of_subscribers_in_a_valid_csv_file()
    {
        $user = $this->createUser();

        $subscribers = factory(Subscriber::class, 2)->make();

        $original_path = storage_path('app/file.csv');

        $this->makeCsvFileFromSubscribers($original_path, $subscribers);

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path)
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subscribers', ['email' => $subscribers->first()->email]);
        $this->assertDatabaseHas('subscribers', ['email' => $subscribers->last()->email]);
    }

    /** @test */
    function it_should_delete_the_uploaded_file_once_it_has_been_imported()
    {
        $user = $this->createUser();

        $subscribers = factory(Subscriber::class, 2)->make();

        $original_path = storage_path('app/file.csv');

        $this->makeCsvFileFromSubscribers($original_path, $subscribers);

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path)
            ]);

        $this->assertEmpty(Storage::disk('local')->allFiles(storage_path('imports')));
    }

    /** @test */
    function it_should_attach_each_subscriber_to_a_segment_if_provided()
    {
        $user = $this->createUser();

        $subscribers = factory(Subscriber::class, 2)->make();
        $segment = factory(Segment::class)->create();

        $original_path = storage_path('app/file.csv');

        $this->makeCsvFileFromSubscribers($original_path, $subscribers);

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path),
                'segments' => [$segment->id]
            ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => Subscriber::first()->id
        ]);
    }

    /** @test */
    function it_should_update_an_existing_subscriber_by_email_if_present_in_the_csv_file()
    {
        $user = $this->createUser();

        $existing_subscriber = factory(Subscriber::class)->create([
            'email' => 'john.doe@email.com',
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $provided_subscriber = factory(Subscriber::class)->make([
            'email' => $existing_subscriber->email
        ]);

        $original_path = storage_path('app/file.csv');

        $this->makeCsvFileFromSubscribers($original_path, collect([$provided_subscriber]));

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path)
            ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => $existing_subscriber->email,
            'first_name' => $provided_subscriber->first_name,
            'last_name' => $provided_subscriber->last_name
        ]);
    }

    /** @test */
    function it_should_update_an_existing_subscriber_by_id_if_present_in_the_csv_file()
    {
        $user = $this->createUser();

        $existing_subscriber = factory(Subscriber::class)->create();
        $provided_subscriber = factory(Subscriber::class)->make();

        $original_path = storage_path('app/file.csv');

        file_put_contents($original_path,
            "id,email,first_name,last_name\n" .
            $existing_subscriber->id . "," . $provided_subscriber->email . "," . $provided_subscriber->first_name . "," . $provided_subscriber->last_name . "\n"
        );

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path)
            ]);

        $this->assertDatabaseHas('subscribers', [
            'id' => $existing_subscriber->id,
            'email' => $provided_subscriber->email,
            'first_name' => $provided_subscriber->first_name,
            'last_name' => $provided_subscriber->last_name
        ]);
    }

    /** @test */
    function it_should_add_the_segments_to_existing_subscriber_segments_during_import()
    {
        $user = $this->createUser();

        $existing_subscriber = factory(Subscriber::class)->create([
            'email' => 'email_impossible_to_guess@email.com',
        ]);

        $subscriber = factory(Subscriber::class)->make();

        $segment = factory(Segment::class)->create();
        $segment2 = factory(Segment::class)->create();

        $existing_subscriber->first()->segments()->attach($segment);

        $original_path = storage_path('app/file.csv');

        file_put_contents($original_path,
            "id,email,first_name,last_name\n" .
            $existing_subscriber->id . "," . $existing_subscriber->email . "," . $existing_subscriber->first_name . "," . $existing_subscriber->last_name . "\n" .
            $subscriber->id . "," . $subscriber->email . "," . $subscriber->first_name . "," . $subscriber->last_name . "\n"
        );

        $response = $this->actingAs($user, 'web')
            ->post(route('subscribers.import.store'), [
                'file' => $this->fakeUploadedFile($original_path),
                'segments' => [$segment2->id]
            ]);

        $this->assertDatabaseHas('segment_subscriber', ['segment_id' => $segment->id, 'subscriber_id' => $existing_subscriber->id]);
        $this->assertDatabaseHas('segment_subscriber', ['segment_id' => $segment2->id, 'subscriber_id' => $existing_subscriber->id]);
    }

    protected function makeCsvFileFromSubscribers($path, $subscribers)
    {
        $csv_content = "";

        foreach ($subscribers as $subscriber) {
            $csv_content .= $subscriber->email . "," . $subscriber->first_name . "," . $subscriber->last_name . "\n";
        }

        file_put_contents($path,
            "email,first_name,last_name\n" .
            $csv_content
        );
    }

    protected function fakeUploadedFile(string $path)
    {
        $size = filesize($path);
        $mime = mime_content_type($path);

        $file = new UploadedFile($path, basename($path), $mime, $size, $error = null, $test = true);

        return $file;
    }
}
