<template>
  <card :title="$t('all_modules')" class="mt-2 mt-md-0">
    <transition name="fade" appear mode="out-in">
      <Loader v-if="loading" key="loading" />
      <div v-else key="data" class="row px-3">
        <div v-for="room in rooms" class="col-12">
          <div class="d-inline-flex align-items-center">
            <h4 class="mb-0">{{ room.name }}</h4>
            <router-link :to="{ name: 'home.settings.room.update', params: { id: room.id } }" class="text-black-50 ms-3 mt-1">
              <fa icon="cog" />
            </router-link>
          </div>
          <div v-if="room.modules.length > 0" class="row mt-3">
            <div v-for="module in room.modules" :key="module.id" class="col-lg-6 col-xxl-4 col-md-12 col-12">
              <home-module-info :module="module" />
            </div>
          </div>
          <div v-else class="row">
            <div class="col-12 mt-3">
              <p class="text-muted">
                Нет устройств
              </p>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </card>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  name: 'Index',
  data: () => ({
    title: window.config.appName + 'Главна страница системы',
    modules: [],
    rooms: [],
    loading: true,
    interval: null
  }),
  metaInfo () {
    return { title: 'Главна страница системы' }
  },
  computed: mapGetters({
    authenticated: 'auth/check'
  }),
  mounted () {
    axios.get('/api/module')
      .then(response => {
        this.rooms = response.data.rooms
        // this.modules = response.data.modules
        this.loading = false
      })
      .catch(error => {
        console.log(error)
      })
    this.interval = setInterval(() => {
      axios.get('/api/module')
        .then(response => {
          // this.modules = response.data.modules
          this.rooms = response.data.rooms
        })
        .catch(error => {
          console.log(error)
        })
    }, 3000)
  },
  beforeDestroy () {
    clearInterval(this.interval)
  }
}
</script>

<style scoped>

</style>
