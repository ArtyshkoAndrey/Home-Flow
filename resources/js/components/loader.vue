<template>
  <div key="loader" class="d-flex align-items-center justify-content-center w-100">
    <div class="spinner-border" role="status" aria-hidden="true" />
  </div>
</template>

<script>
export default {
  name: 'Loader'
}
</script>

<style>

.fade-enter-active, .fade-leave-active {
  transition: opacity .2s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}

</style>
