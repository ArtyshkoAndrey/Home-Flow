<template>
  <card :title="$t('your_info')">
    <div class="row">
      <div class="col-md-10 col-12">
        <form @submit.prevent="update"
              @keydown="form.onKeydown($event)"
        >
          <alert-success :form="form"
                         :message="$t('info_updated')"
          />
          <!-- Name -->
          <div class="row mb-3">
            <label class="col-md-4 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-8">
              <input v-model="form.name"
                     :class="{ 'is-invalid': form.errors.has('name') }"
                     class="form-control"
                     type="text"
                     name="name"
              >
              <has-error :form="form"
                         field="name"
              />
            </div>
          </div>

          <!-- Email -->
          <div class="row mb-3">
            <label class="col-md-4 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-8">
              <input v-model="form.email"
                     :class="{ 'is-invalid': form.errors.has('email') }"
                     class="form-control"
                     type="email"
                     name="email"
              >
              <has-error :form="form"
                         field="email"
              />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row justify-content-end">
            <div class="col-auto">
              <v-button :loading="form.busy"
                        type="success"
              >
                {{ $t('update') }}
              </v-button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return {title: this.$t('settings')}
  },

  data: () => ({
    form: new Form({
      name: '',
      email: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      await this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
