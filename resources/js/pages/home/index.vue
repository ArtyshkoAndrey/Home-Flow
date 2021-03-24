<template>
  <card :title="$t('all_modules')">
    <transition name="fade" appear mode="out-in">
      <Loader v-if="loading" key="loading" />
      <div v-else key="data" class="row">
        <div v-for="module in modules" :key="module.id" class="col-md-6 col-12">
          <home-module-info :module="module" />
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
        this.modules = response.data.modules
        this.loading = false
      })
      .catch(error => {
        console.log(error)
      })
    this.interval = setInterval(() => {
      axios.get('/api/module')
        .then(response => {
          this.modules = response.data.modules
        })
        .catch(error => {
          console.log(error)
        })
    }, 10000)
  },
  beforeDestroy () {
    clearInterval(this.interval)
  }
}
</script>

<style scoped>

</style>
