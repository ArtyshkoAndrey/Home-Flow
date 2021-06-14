<template>
  <card :title="$t('all_modules')" class="mt-2 mt-md-0">
    <transition name="fade" appear mode="out-in">
      <Loader v-if="loading" key="loading" />
      <div v-else key="data" class="row px-3">
        <div v-for="room in rooms" class="col-12">
          <h4>{{ room.name }}</h4>

          <div class="row">
            <div v-for="module in room.modules" :key="module.id" class="col-lg-6 col-xxl-4 col-md-12 col-12">
              <home-module-info :module="module" />
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
