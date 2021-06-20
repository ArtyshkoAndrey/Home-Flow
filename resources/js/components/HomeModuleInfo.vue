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
              <router-link :to="{ name: 'home.settings.module.update', params: { id: module.id } }" class="text-black-50 ms-auto">
                <fa icon="cog" />
              </router-link>
            </div>

            <div v-if="module.data === null">
              <p class="card-text">
                <strong class="text-muted">Нет данных</strong>
                <br>
                <small class="text-muted">{{ module.updated_date }}</small>
              </p>
            </div>

            <div v-else-if="module.type.type === 'temperature' || module.type.type === 'humidity'">
              <p class="card-text">
                <strong class="text-primary">{{ module.data }} {{ icoType }}</strong>
                <br>
                <small class="text-muted">{{ module.updated_date }}</small>
              </p>
            </div>

            <div v-else-if="module.type.type === 'switch' || module.type.type === 'light'">
              <p class="card-text">
<!--                <strong v-if="module.data === '1'" class="text-primary">Вкл.</strong>-->
<!--                <strong v-else class="text-danger">Выкл.</strong>-->
                <label class="switch">
                  <input v-model="computedSwitchLightDataToBoolean" type="checkbox">
                  <span class="slider round" />
                </label>
                <br>
                <small class="text-muted">{{ module.updated_date }}</small>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

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
        return '°C'
      } else if (this.module.type.type === 'humidity') {
        return '%'
      }
      return 'Ошибка'
    },
    computedSwitchLightDataToBoolean: {
      get: function () {
        return this.module.data === '1'
      },
      set: function (newValue) {
        // eslint-disable-next-line vue/no-mutating-props
        this.module.data = newValue ? '1' : '0'
        console.log(newValue)
        axios.put('/api/module/' + this.module.id, {
          data: newValue ? '1' : '0'
        })
          .then(response => {
            console.log(response.data)
            // eslint-disable-next-line vue/no-mutating-props
            this.module.data = response.data.module.data
          })
          .catch(error => {
            console.log(error.response.data)
          })
      }
    }
  },
  methods: {
    onChangeEventHandlerSwitchLightBool (newValue) {
      axios.put('/api/module/' + this.module.id, {
        data: newValue ? '1' : '0'
      })
        .then(response => {
          console.log(response.data)
          // eslint-disable-next-line vue/no-mutating-props
          this.module.data = response.data.module.data
        })
        .catch(error => {
          console.log(error.response.data)
        })
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
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
