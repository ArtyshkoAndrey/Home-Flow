<template>
  <card :title="$t('your_password')">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
          <alert-success :form="form" :message="$t('password_updated')" />

          <!-- Password -->
          <div class="row mb-3">
            <label class="col-sm-4 col-form-label text-md-right">{{ $t('new_password') }}</label>
            <div class="col-sm-7">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
              <has-error :form="form" field="password" />
            </div>
          </div>

          <!-- Password Confirmation -->
          <div class="row mb-3">
            <label class="col-sm-4 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
            <div class="col-sm-7">
              <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation">
              <has-error :form="form" field="password_confirmation" />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row justify-content-end">
            <div class="col-auto">
              <v-button :loading="form.busy" type="success">
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

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async update () {
      await this.form.patch('/api/settings/password')

      this.form.reset()
    }
  }
}
</script>
