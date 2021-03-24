<template>
  <div class="card mb-3 rounded-0 border-0 shadow-none">
    <div class="card-body p-0">
      <div class="row g-0 d-flex align-items-stretch">
        <div class="col-md-4"
        >
<!--          <div :class="'bg-primary'" class="p-4 rounded-circle d-flex justify-content-center align-items-center text-center">-->
<!--            <i class="fa-2x text-white" :class="module.ico"></i>-->
<!--          </div>-->
          <div class="square">
            <div class="square-content text-white d-flex align-items-center justify-content-center" :class="'bg-primary'">
              <i class="fa-2x text-white" :class="module.ico"></i>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <div class="card-title d-flex">
              <h5>
                {{ module.name }}
              </h5>

              <a href="#" class="text-black-50 ms-auto">
                <fa icon="cog" />
              </a>
            </div>
            <p class="card-text">
              <small class="text-muted">{{ module.data }} {{ type }}</small>
              <br>
              <small class="text-muted"></small>
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
    module: { type: Object, default: null }
  },
  computed: {
    type: function () {
      if (this.module.type.type === 'action.devices.types.SENSOR') {
        return '%'
      }
      return ''
    }
  }
}
</script>

<style scoped>
.square {
  position: relative;
  display: block;

}

.square:after {
  content: '';
  display: block;
  padding-top: 100%;
  /*background: #000;*/
  border-radius: 100%;
}

.square-content {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 80%;
  height: 80%;
  transform:translate(-50%, -50%);
  border-radius: 100%;
}
</style>
