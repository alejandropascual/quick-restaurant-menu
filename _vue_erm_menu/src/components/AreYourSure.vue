<template>
  <div class="p-0">

    <slot />

    <!-- BUTTONS -->
    <div class="border border-t border-slate-200 p-4 flex items-center gap-2">
      <div class="cursor-pointer ml-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-400" @click.prevent="$emit('cancel')">{{ __('Cancel') }}</div>
      <div class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-400" @click.prevent="$emit('confirm')">{{ __('OK') }}</div>
    </div>

  </div>
</template>

<script>
export default {
  emits: ['cancel', 'confirm'],

  methods: {
    __(label) { // @todo
      return label
    },
  }
}
</script>
