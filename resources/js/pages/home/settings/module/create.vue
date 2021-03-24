<template>
  <card :title="$t('home.settings.module.create.title')">
    <div class="row">
      <form @submit.prevent="create" @keydown="form.onKeydown($event)">
        <!-- Email -->
        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
          <div class="col-md-7">
            <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control"
                   type="text" name="name"
            >
            <has-error :form="form" field="name" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.g_index') }} <span class="text-muted">(Google Home)</span></label>
          <div class="col-md-7">
            <input v-model="form.google_index"
                   :class="{ 'is-invalid': form.errors.has('google_index') }"
                   class="form-control"
                   type="text"
                   name="google_index"
            >
            <has-error :form="form" field="google_index" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.type') }}</label>
          <div class="col-md-7">
            <select id="type" v-model="form.type"
                    name="type"
                    :class="{ 'is-invalid': form.errors.has('type') }"
                    class="form-control"
            >
              <option v-for="type in types" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
            <has-error :form="form" field="type" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.traits') }}</label>
          <div class="col-md-7">
            <select id="traits" v-model="form.traits"
                    name="traits"
                    :class="{ 'is-invalid': form.errors.has('traits') }"
                    class="form-control"
                    multiple
            >
              <option v-for="trait in traits" :key="trait.id" :value="trait.id">
                {{ trait.name }}
              </option>
            </select>
            <has-error :form="form" field="traits" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.room') }}</label>
          <div class="col-md-7">
            <select id="room" v-model="form.room"
                    name="room"
                    :class="{ 'is-invalid': form.errors.has('room') }"
                    class="form-control"
            >
              <option v-for="room in rooms" :key="room.id" :value="room.id">
                {{ room.name }}
              </option>
            </select>
            <has-error :form="form" field="root" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">MQTT</label>
          <div class="col-md-7">
            <input v-model="form.mqtt"
                   :class="{ 'is-invalid': form.errors.has('mqtt') }"
                   class="form-control"
                   type="text"
                   name="MQTT"
            >
            <has-error :form="form" field="mqtt" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.module.form.ico') }}</label>
          <div class="col-md-7">
            <font-awesome-picker v-model="form.ico" :value="'d'"></font-awesome-picker>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-7 offset-md-3 d-flex">
            <!-- Submit Button -->
            <v-button :loading="form.busy">
              {{ $t('save') }}
            </v-button>
          </div>
        </div>
      </form>
    </div>
  </card>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import FontAwesomePicker from "bootstrap-vue-font-awesome-picker";
import {mapGetters} from "vuex";

export default {
  name: 'Create',
  components: {
    FontAwesomePicker
  },
  data: () => ({
    form: new Form({
      name: '',
      google_index: '',
      type: '',
      traits: [],
      mqtt: '',
      ico: ''
    }),
    types: [],
    traits: [],
    rooms: []
  }),
  metaInfo () {
    return { title: 'Создание нового модуля' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async create () {
      console.log(this.form)
      const { data } = await this.form.post('/api/module')
    }
  },
  mounted () {
    axios.get('/api/google/types')
      .then(response => {
        this.types = response.data.types
      })
      .catch(error => {
        console.warn(error.response.data)
      })

    axios.get('/api/google/traits')
      .then(response => {
        this.traits = response.data.traits
      })
      .catch(error => {
        console.warn(error.response.data)
      })

    axios.get('/api/room')
      .then(response => {
        this.rooms = response.data.rooms
      })
      .catch(error => {
        console.warn(error.response.data)
      })
  }
}
</script>

<style scoped>

</style>
