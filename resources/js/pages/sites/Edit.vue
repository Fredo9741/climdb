<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- En-tête principal -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier le site</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ site.nom }}</p>
              </div>
              <Link
                :href="route('sites.index')"
                class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800"
              >
                <Icon name="ArrowLeft" class="h-4 w-4 mr-2 inline" /> Retour
              </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Informations de base -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informations de base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Nom du site -->
                  <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">
                      Nom du site *
                    </label>
                    <input
                      id="nom"
                      v-model="form.nom"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: Usine principale, Bureaux, Entrepôt..."
                      required
                    />
                    <div v-if="form.errors.nom" class="mt-1 text-sm text-red-600">
                      {{ form.errors.nom }}
                    </div>
                  </div>

                  <!-- Client -->
                  <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">
                      Client propriétaire *
                    </label>
                    <select
                      id="client_id"
                      v-model="form.client_id"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    >
                      <option value="">Sélectionner un client</option>
                      <option
                        v-for="client in clients"
                        :key="client.id"
                        :value="client.id"
                      >
                        {{ client.nom }}
                      </option>
                    </select>
                    <div v-if="form.errors.client_id" class="mt-1 text-sm text-red-600">
                      {{ form.errors.client_id }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Adresse -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Adresse du site</h3>
                <div class="space-y-4">
                  <!-- Adresse -->
                  <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700">
                      Adresse *
                    </label>
                    <input
                      id="adresse"
                      v-model="form.adresse"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 123 rue de la République"
                      required
                    />
                    <div v-if="form.errors.adresse" class="mt-1 text-sm text-red-600">
                      {{ form.errors.adresse }}
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Code postal -->
                    <div>
                      <label for="code_postal" class="block text-sm font-medium text-gray-700">
                        Code postal *
                      </label>
                      <input
                        id="code_postal"
                        v-model="form.code_postal"
                        type="text"
                        maxlength="5"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ex: 75001"
                        required
                      />
                      <div v-if="form.errors.code_postal" class="mt-1 text-sm text-red-600">
                        {{ form.errors.code_postal }}
                      </div>
                    </div>

                    <!-- Ville -->
                    <div>
                      <label for="ville" class="block text-sm font-medium text-gray-700">
                        Ville *
                      </label>
                      <input
                        id="ville"
                        v-model="form.ville"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ex: Paris"
                        required
                      />
                      <div v-if="form.errors.ville" class="mt-1 text-sm text-red-600">
                        {{ form.errors.ville }}
                      </div>
                    </div>

                    <!-- Pays -->
                    <div>
                      <label for="pays" class="block text-sm font-medium text-gray-700">
                        Pays *
                      </label>
                      <input
                        id="pays"
                        v-model="form.pays"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ex: France"
                        required
                      />
                      <div v-if="form.errors.pays" class="mt-1 text-sm text-red-600">
                        {{ form.errors.pays }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Coordonnées GPS -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Coordonnées GPS (optionnel)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Latitude -->
                  <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">
                      Latitude
                    </label>
                    <input
                      id="latitude"
                      v-model="form.latitude"
                      type="number"
                      step="any"
                      min="-90"
                      max="90"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 48.8566"
                    />
                    <div v-if="form.errors.latitude" class="mt-1 text-sm text-red-600">
                      {{ form.errors.latitude }}
                    </div>
                  </div>

                  <!-- Longitude -->
                  <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">
                      Longitude
                    </label>
                    <input
                      id="longitude"
                      v-model="form.longitude"
                      type="number"
                      step="any"
                      min="-180"
                      max="180"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 2.3522"
                    />
                    <div v-if="form.errors.longitude" class="mt-1 text-sm text-red-600">
                      {{ form.errors.longitude }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contacts -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Contacts du site</h3>
                <div class="space-y-4">
                  <div v-for="(contact, index) in form.contacts" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                      <div>
                        <label for="contact_nom" class="block text-sm font-medium text-gray-700">
                          Nom *
                        </label>
                        <input
                          id="contact_nom"
                          v-model="contact.nom"
                          type="text"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Ex: Dupont"
                          required
                        />
                        <div v-if="form.errors[`contacts.${index}.nom`]" class="mt-1 text-sm text-red-600">
                          {{ form.errors[`contacts.${index}.nom`] }}
                        </div>
                      </div>
                      <div>
                        <label for="contact_prenom" class="block text-sm font-medium text-gray-700">
                          Prénom *
                        </label>
                        <input
                          id="contact_prenom"
                          v-model="contact.prenom"
                          type="text"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Ex: Jean"
                          required
                        />
                        <div v-if="form.errors[`contacts.${index}.prenom`]" class="mt-1 text-sm text-red-600">
                          {{ form.errors[`contacts.${index}.prenom`] }}
                        </div>
                      </div>
                      <div>
                        <label for="contact_fonction" class="block text-sm font-medium text-gray-700">
                          Fonction *
                        </label>
                        <input
                          id="contact_fonction"
                          v-model="contact.fonction"
                          type="text"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Ex: Responsable de la sécurité"
                          required
                        />
                        <div v-if="form.errors[`contacts.${index}.fonction`]" class="mt-1 text-sm text-red-600">
                          {{ form.errors[`contacts.${index}.fonction`] }}
                        </div>
                      </div>
                      <div>
                        <label for="contact_telephone" class="block text-sm font-medium text-gray-700">
                          Téléphone
                        </label>
                        <input
                          id="contact_telephone"
                          v-model="contact.telephone"
                          type="text"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Ex: 01 23 45 67 89"
                        />
                        <div v-if="form.errors[`contacts.${index}.telephone`]" class="mt-1 text-sm text-red-600">
                          {{ form.errors[`contacts.${index}.telephone`] }}
                        </div>
                      </div>
                      <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">
                          Email
                        </label>
                        <input
                          id="contact_email"
                          v-model="contact.email"
                          type="email"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Ex: contact@example.com"
                        />
                        <div v-if="form.errors[`contacts.${index}.email`]" class="mt-1 text-sm text-red-600">
                          {{ form.errors[`contacts.${index}.email`] }}
                        </div>
                      </div>
                    </div>
                    <button
                      type="button"
                      @click="removeContact(index)"
                      class="mt-2 text-red-600 hover:underline"
                    >
                      Supprimer ce contact
                    </button>
                  </div>
                  <button
                    type="button"
                    @click="addContact"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  >
                    Ajouter un contact
                  </button>
                </div>
              </div>

              <!-- Boutons d'action -->
              <div class="flex justify-between pt-6 border-t">
                <Link
                  :href="route('sites.show', site.id)"
                  class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  {{ form.processing ? 'Modification...' : 'Modifier le site' }}
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

const props = defineProps<{ site: any; clients: any[]; contacts: any[] }>()

// Formulaire pré-rempli avec les données du site
const form = useForm({
  client_id: props.site.client_id,
  nom: props.site.nom,
  adresse: props.site.adresse,
  ville: props.site.ville,
  code_postal: props.site.code_postal,
  pays: props.site.pays,
  latitude: props.site.latitude,
  longitude: props.site.longitude ?? '',
  contacts: props.contacts.length ? props.contacts.map(c => ({ ...c })) : [],
})

if (form.contacts.length === 0) {
  form.contacts.push({ nom: '', prenom: '', fonction: '', telephone: '', email: '' })
}

const addContact = () => {
  form.contacts.push({ nom: '', prenom: '', fonction: '', telephone: '', email: '' })
}

const removeContact = (index: number) => {
  form.contacts.splice(index, 1)
}

const submit = () => {
  form.put(route('sites.update', props.site.id))
}
</script>