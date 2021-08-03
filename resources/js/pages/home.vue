<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3>{{ $t('root.title') }}</h3>
      </div>
    </div>
    <transition name="fade" appear mode="out-in">

      <Loader v-if="loading" class="mt-5" key="loading" />

      <div v-else class="row align-items-stretch mt-3 ">

        <div class="col-lg-3 col-md-6 col-12 mt-3 mt-md-2 mt-ld-0">
          <div class="row m-0 h-100">
            <div class="col-12 py-3 px-4 rounded-3 text-white bg-success">
              <h5 class="d-block mb-0">Устройств <span class="badge bg-dark">{{ countModules }}</span></h5>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mt-3 mt-md-2 mt-ld-0">
          <div class="row m-0 h-100">
            <div class="col-12 py-3 px-4 rounded-3 text-white bg-primary">
              <h5 class="d-block mb-0">MQTT <span class="badge bg-dark">{{ statusMQTT }}</span></h5>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mt-3 mt-md-2 mt-ld-0">
          <div class="row m-0 h-100">
            <div class="col-12 py-3 px-4 rounded-3 text-white bg-primary">
              <h5 class="d-block mb-0">MYSQL <span class="badge bg-dark">{{ statusMYSQL }}</span></h5>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mt-3 mt-md-2 mt-ld-0">
          <div class="row m-0 h-100">
            <div class="col-12 py-3 px-4 rounded-3 text-white bg-primary">
              <h5 class="d-block mb-0">Версия <span class="badge bg-dark">{{ version }}</span></h5>
            </div>
          </div>
        </div>

      </div>
    </transition>

    <div class="row mt-5">
      <div class="col-md-12">
        <h4>Знакомство</h4>
      </div>
      <div class="col-12">
        <div class="row mt-2">

          <div class="col-lg-8">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Настройте свою собственную систему HomeFlow всего за несколько шагов</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Коротко</h6>
                    <p class="card-text">
                      Программное обеспечение для автоматизации с открытым исходным кодом, независимое от поставщика и технологий, для вашего дома
                    </p>
                    <p class="card-text">
                      HomeFlow  работает на вашем оборудовании, не требует для работы каких-либо облачных сервисов, хранит ваши данные в частном порядке дома и по возможности общается напрямую с вашими локальными устройствами. В основе нашей философии лежит то, что вы всегда контролируете ситуацию.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-12 mt-3 mt-lg-0">
            <div class="card">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Важно</h6>
                <p class="card-text">Регулярно проверяте статус систем. Для перезапуска сервера MQTT требуется ввести комнанда <kbd>php artisan module:run</kbd></p>
              </div>
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
  middleware: 'auth',
  data: () => ({
    title: window.config.appName + 'Системная информация',
    countModules: null,
    statusMQTT: 'online',
    statusMYSQL: 'online',
    version: '1.0.1',
    loading: true
  }),
  metaInfo () {
    return { title: this.$t('index') }
  },
  mounted() {
    axios.get('/api/status')
      .then(response => {
        this.countModules = response.data.countModules
        this.loading = false
      })
      .catch(error => {
        console.log(error)
      })
  }

}
</script>
