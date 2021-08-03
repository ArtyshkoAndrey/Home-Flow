<template>
  <card :title="$t('home.settings.room.create.title')">
    <div class="row">
      <form @keydown="form.onKeydown($event)" @submit.prevent="create">
        <!-- Email -->
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
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.room.form.index') }} <span
            class="text-muted"
          >(Google Home)</span></label>
          <div class="col-md-7">
            <input v-model="form.index"
                   :class="{ 'is-invalid': form.errors.has('index') }"
                   class="form-control"
                   name="index"
                   type="text"
            >
            <has-error :form="form" field="index" />
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('home.settings.room.form.description') }}</label>
          <div class="col-md-7">
            <input v-model="form.description"
                   :class="{ 'is-invalid': form.errors.has('description') }"
                   class="form-control"
                   name="description"
                   type="text"
            >
            <has-error :form="form" field="description" />
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
import { mapGetters } from 'vuex'

export default {
  name: 'Create',
  components: {
  },
  data: () => ({
    form: new Form({
      name: '',
      index: '',
      description: ''
    })
  }),
  metaInfo () {
    return { title: 'Создание новой комнаты' }
  },
  computed: mapGetters({
    user: 'auth/user'
  }),
  mounted () {

  },
  methods: {
    async create () {
      console.log(this.form)
      // eslint-disable-next-line no-unused-vars
      const { data } = await this.form.post('/api/room')

      await this.$router.push({ name: 'home.index' })
    }
  }
}
</script>

<style scoped>

</style>
