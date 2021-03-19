<template>
  <div class="card mb-3 rounded-0 border-0 shadow-none">
    <div class="card-body p-0">
      <div class="row g-0 d-flex align-items-stretch">
        <div class="col-md-4 d-flex justify-content-center align-items-center text-center"
        >
          <div :class="bgColor" class="p-4 rounded-circle d-flex justify-content-center align-items-center text-center">
            <fa :icon="ico"
                class="text-white fa-2x"
            />
          </div>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <div class="card-title d-flex">
              <h5>
                {{ title }}
              </h5>

              <a href="#" class="text-black-50 ms-auto">
                <fa icon="cog" />
              </a>
            </div>
            <p class="card-text">
              <small class="text-muted">{{ info }} {{ type }}</small>
              <br>
              <small class="text-muted">{{ lastUpdate }}</small>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeModuleInfo',
  props: {
    title: {type: String, default: null},
    info: {type: String, default: null},
    type: {type: String, default: null},
    lastUpdate: {type: String, default: null},
    ico: {type: String, default: 'home'},
    bgColor: {type: String, default: 'primary'}
  }
}
</script>

<style scoped>

</style>
