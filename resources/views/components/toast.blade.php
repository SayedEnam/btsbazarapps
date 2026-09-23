{{--
    Reusable toast host. Any Livewire component can trigger a toast with:
        $this->dispatch('notify', type: 'success', message: 'Saved successfully.');
    Types: success | error | warning | info
--}}
<div
    x-data="{
        toasts: [],
        push(toast) {
            toast.id = Date.now() + Math.random();
            this.toasts.push(toast);
            setTimeout(() => this.remove(toast.id), 5000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
    }"
    @notify.window="push($event.detail)"
    class="toast-container position-fixed top-0 end-0 p-3"
    style="z-index: 1080;"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            class="toast align-items-center border-0 show mb-2 text-white"
            :class="{
                'bg-success': toast.type === 'success',
                'bg-danger': toast.type === 'error',
                'bg-warning text-dark': toast.type === 'warning',
                'bg-info text-dark': toast.type === 'info' || !toast.type,
            }"
            role="alert"
        >
            <div class="d-flex">
                <div class="toast-body" x-text="toast.message"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="remove(toast.id)"></button>
            </div>
        </div>
    </template>
</div>
