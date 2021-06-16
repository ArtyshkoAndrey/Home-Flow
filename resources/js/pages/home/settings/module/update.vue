<template>
  <card :title="$t('home.settings.module.update.title')">
    <transition name="fade" appear mode="out-in">
      <Loader v-if="loading" key="loading" />
      <div v-else class="row">
        <form @keydown="form.onKeydown($event)" @submit.prevent="update">
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                     name="name" type="text"
              >
              <has-error :form="form" field="name" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.g_index') }} <span
              class="text-muted"
            >(Google Home)</span></label>
            <div class="col-md-7">
              <input v-model="form.google_index"
                     :class="{ 'is-invalid': form.errors.has('google_index') }"
                     class="form-control"
                     name="google_index"
                     type="text"
              >
              <has-error :form="form" field="google_index" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.type') }}</label>
            <div class="col-md-7">
              <select id="type" v-model="form.type"
                      :class="{ 'is-invalid': form.errors.has('type') }"
                      class="form-control"
                      name="type"
              >
                <option v-for="type in types" :key="type.id" :value="type.id">
                  {{ type.name }}
                </option>
              </select>
              <has-error :form="form" field="type" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.room') }}</label>
            <div class="col-md-7">
              <select id="room" v-model="form.room"
                      :class="{ 'is-invalid': form.errors.has('room') }"
                      class="form-control"
                      name="room"
              >
                <option v-for="room in rooms" :key="room.id" :value="room.id">
                  {{ room.name }}
                </option>
              </select>
              <has-error :form="form" field="room" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">MQTT</label>
            <div class="col-md-7">
              <input v-model="form.mqtt"
                     :class="{ 'is-invalid': form.errors.has('mqtt') }"
                     class="form-control"
                     name="MQTT"
                     type="text"
              >
              <has-error :form="form" field="mqtt" />
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.ico') }}</label>
            <div class="col-md-7">
              <font-awesome-picker v-model="form.ico" :class="{ 'is-invalid': form.errors.has('ico') }" />
              <has-error :form="form" field="ico" />
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-auto offset-md-3 d-flex">
              <!-- Submit Button -->
              <v-button :loading="form.busy">
                {{ $t('save') }}
              </v-button>
            </div>
            <div class="col-md-auto d-flex">
              <!-- Submit Button -->
              <button type="button" class="btn btn-danger" @click="deleteSwal">
                Удалить
              </button>
            </div>
          </div>
        </form>
      </div>
    </transition>
  </card>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import FontAwesomePicker from 'bootstrap-vue-font-awesome-picker'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  name: 'Update',
  components: {
    FontAwesomePicker
  },
  data: () => ({
    form: new Form({
      name: '',
      google_index: '',
      type: '',
      mqtt: '',
      ico: ''
    }),
    formDelete: new Form(),
    types: [],
    rooms: [],
    loading: true
  }),
  metaInfo () {
    return { title: 'Редактирование модуля' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  async mounted () {
    // console.log(this.$router.currentRoute.params.id)
    await axios.get('/api/types')
      .then(response => {
        this.types = response.data.types
      })
      .catch(error => {
        console.warn(error.response.data)
      })

    await axios.get('/api/google/traits')
      .then(response => {
        this.traits = response.data.traits
      })
      .catch(error => {
        console.warn(error.response.data)
      })

    await axios.get('/api/room')
      .then(response => {
        this.rooms = response.data.rooms
      })
      .catch(error => {
        console.warn(error.response.data)
      })
    await axios.get('/api/module/' + this.$router.currentRoute.params.id)
      .then(response => {
        this.form.name = response.data.module.name
        this.form.id = response.data.module.id
        this.form.mqtt = response.data.module.mqtt
        this.form.google_index = response.data.module.google_index
        this.form.room = response.data.module.room_id
        this.form.type = response.data.module.type_id
        this.form.ico = response.data.module.ico
      })
      .catch(error => {
        console.warn(error.response.data)
        this.$router.push({ name: 'home.index' })
      })
    this.loading = false
  },
  methods: {
    async update () {
      console.log(this.form)
      // eslint-disable-next-line no-unused-vars
      const { data } = await this.form.put('/api/module/' + this.$router.currentRoute.params.id)

      await this.$router.push({ name: 'home.index' })
    },
    async deleteSwal () {
      Swal.fire({
        title: 'Вы точно хотите удалить устройство?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Не удалять',
        denyButtonText: this.$t('delete')
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isDenied) {
          this.delete()
        }
      })
    },
    delete () {
      // eslint-disable-next-line no-unused-vars
      const { data } = this.form.delete('/api/module/' + this.$router.currentRoute.params.id)

      this.$router.push({ name: 'home.index' })
    }
  }
}
</script>

<style scoped>

</style>
