<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Modifier le client</h2>
                <p class="text-gray-600">{{ client.nom }}</p>
              </div>
              <Link
                :href="route('clients.show', client.id)"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
              >
                Annuler
              </Link>
            </div>

            <form @submit.prevent="submit">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                  <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de l'entreprise *
                  </label>
                  <input
                    id="nom"
                    v-model="form.nom"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.nom }"
                    required
                  />
                  <div v-if="errors?.nom" class="mt-1 text-sm text-red-600">
                    {{ errors.nom }}
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                  </label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.email }"
                    required
                  />
                  <div v-if="errors?.email" class="mt-1 text-sm text-red-600">
                    {{ errors.email }}
                  </div>
                </div>

                <!-- Téléphone -->
                <div>
                  <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                    Téléphone *
                  </label>
                  <input
                    id="telephone"
                    v-model="form.telephone"
                    type="tel"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.telephone }"
                    required
                  />
                  <div v-if="errors?.telephone" class="mt-1 text-sm text-red-600">
                    {{ errors.telephone }}
                  </div>
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                  <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse *
                  </label>
                  <textarea
                    id="adresse"
                    v-model="form.adresse"
                    rows="3"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.adresse }"
                    required
                  ></textarea>
                  <div v-if="errors?.adresse" class="mt-1 text-sm text-red-600">
                    {{ errors.adresse }}
                  </div>
                </div>

                <!-- Ville -->
                <div>
                  <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">
                    Ville *
                  </label>
                  <input
                    id="ville"
                    v-model="form.ville"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.ville }"
                    required
                  />
                  <div v-if="errors?.ville" class="mt-1 text-sm text-red-600">
                    {{ errors.ville }}
                  </div>
                </div>

                <!-- Code postal -->
                <div>
                  <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">
                    Code postal *
                  </label>
                  <input
                    id="code_postal"
                    v-model="form.code_postal"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.code_postal }"
                    required
                  />
                  <div v-if="errors?.code_postal" class="mt-1 text-sm text-red-600">
                    {{ errors.code_postal }}
                  </div>
                </div>

                <!-- Pays -->
                <div class="md:col-span-2">
                  <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">
                    Pays *
                  </label>
                  <input
                    id="pays"
                    v-model="form.pays"
                    type="text"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-300': errors?.pays }"
                    required
                  />
                  <div v-if="errors?.pays" class="mt-1 text-sm text-red-600">
                    {{ errors.pays }}
                  </div>
                </div>
              </div>

              <div class="mt-8 flex justify-end space-x-3">
                <Link
                  :href="route('clients.show', client.id)"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="form.processing">Mise à jour...</span>
                  <span v-else>Mettre à jour</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

interface Client {
  id: number
  nom: string
  email: string
  telephone: string
  adresse: string
  ville: string
  code_postal: string
  pays: string
}

const props = defineProps<{
  client: Client
  errors?: Record<string, string>
}>()

const form = useForm({
  nom: props.client.nom,
  email: props.client.email,
  telephone: props.client.telephone,
  adresse: props.client.adresse,
  ville: props.client.ville,
  code_postal: props.client.code_postal,
  pays: props.client.pays,
})

const submit = () => {
  form.put(route('clients.update', props.client.id), {
    onSuccess: () => {
      // La redirection sera gérée par le contrôleur
    },
  })
}
</script> 