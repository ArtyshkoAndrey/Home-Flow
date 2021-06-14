<template>
  <div class="card mb-3 rounded-0 border-0 shadow-none">
    <div class="card-body p-0">
      <div class="row g-0 d-flex align-items-stretch">
        <div class="col-md-4 col-4">
          <div class="square">
            <div class="square-content text-white d-flex align-items-center justify-content-center"
                 :class=" module.data === null ? 'bg-danger' : 'bg-primary'"
            >
              <i class="fa-2x text-white" :class="module.ico" />
            </div>
          </div>
        </div>
        <div class="col-md-8 col">
          <div class="card-body">
            <div class="card-title d-flex">
              <h5>
                {{ module.name }}
              </h5>

              <a href="#" class="text-black-50 ms-auto">
                <fa icon="cog" />
              </a>
            </div>

            <div v-if="module.data === null">
              <p class="card-text">
                <strong class="text-muted">Нет данных</strong>
              </p>
            </div>

            <div v-else-if="module.type.type === 'temperature' || module.type.type === 'humidity'">
              <p class="card-text">
                <strong class="text-primary">{{ module.data }} {{ icoType }}</strong>
                <br>
                <small class="text-muted"/>
              </p>
            </div>

            <div v-else-if="module.type.type === 'switch' || module.type.type === 'light'">
              <p class="card-text">
                <strong v-if="module.data === '1'" class="text-primary">Включён</strong>
                <strong v-else class="text-danger">Выключён</strong>
                <br>
                <small class="text-muted"></small>
              </p>
            </div>
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
    module: {
      type: Object,
      default: null
    }
  },
  computed: {
    icoType: function () {
      if (this.module.type.type === 'temperature') {
        return 'C'
      } else if (this.module.type.name === 'humidity') {
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
  transform: translate(-50%, -50%);
  border-radius: 100%;
}

.ico:after {
  display: block;
  /*content: this.module.ico;*/
  font-family: 'Font Awesome 5 Free', serif
}
</style>
