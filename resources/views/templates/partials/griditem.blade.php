<div class="template-panel">
    <a href="{{ route('templates.edit', $template->id) }}" style="display:block">
        <div class="template-preview">
            <iframe width="100%" scrolling="no" frameborder="0" srcdoc="{{ $template->content }}"></iframe>
        </div>
    </a>
</div>