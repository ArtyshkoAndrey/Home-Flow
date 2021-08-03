<template>
  <div class="card">
    <div v-if="title" class="card-header py-2 font-weight-bolder">
      {{ title }}
    </div>

    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  }
}
</script>
