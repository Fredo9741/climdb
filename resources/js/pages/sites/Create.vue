<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- En-tête principal -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nouveau site</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Créer un nouveau site client</p>
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

                  <!-- Type de site -->
                  <div>
                    <label for="type_site" class="block text-sm font-medium text-gray-700">
                      Type de site
                    </label>
                    <select
                      id="type_site"
                      v-model="form.type_site"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="">Sélectionner un type</option>
                      <option value="bureau">Bureau</option>
                      <option value="usine">Usine</option>
                      <option value="entrepot">Entrepôt</option>
                      <option value="magasin">Magasin</option>
                      <option value="hotel">Hôtel</option>
                      <option value="restaurant">Restaurant</option>
                      <option value="hopital">Hôpital</option>
                      <option value="ecole">École</option>
                      <option value="autre">Autre</option>
                    </select>
                    <div v-if="form.errors.type_site" class="mt-1 text-sm text-red-600">
                      {{ form.errors.type_site }}
                    </div>
                  </div>

                  <!-- Surface -->
                  <div>
                    <label for="surface" class="block text-sm font-medium text-gray-700">
                      Surface (m²)
                    </label>
                    <input
                      id="surface"
                      v-model="form.surface"
                      type="number"
                      min="0"
                      step="0.1"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 1500"
                    />
                    <div v-if="form.errors.surface" class="mt-1 text-sm text-red-600">
                      {{ form.errors.surface }}
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
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 2.3522"
                    />
                    <div v-if="form.errors.longitude" class="mt-1 text-sm text-red-600">
                      {{ form.errors.longitude }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact sur site -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Contact sur site</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Nom du contact -->
                  <div>
                    <label for="contact_nom" class="block text-sm font-medium text-gray-700">
                      Nom du contact
                    </label>
                    <input
                      id="contact_nom"
                      v-model="form.contact_nom"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: Jean Dupont"
                    />
                    <div v-if="form.errors.contact_nom" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contact_nom }}
                    </div>
                  </div>

                  <!-- Téléphone du contact -->
                  <div>
                    <label for="contact_telephone" class="block text-sm font-medium text-gray-700">
                      Téléphone du contact
                    </label>
                    <input
                      id="contact_telephone"
                      v-model="form.contact_telephone"
                      type="tel"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: 01 23 45 67 89"
                    />
                    <div v-if="form.errors.contact_telephone" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contact_telephone }}
                    </div>
                  </div>

                  <!-- Email du contact -->
                  <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700">
                      Email du contact
                    </label>
                    <input
                      id="contact_email"
                      v-model="form.contact_email"
                      type="email"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="contact@site.fr"
                    />
                    <div v-if="form.errors.contact_email" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contact_email }}
                    </div>
                  </div>

                  <!-- Fonction du contact -->
                  <div>
                    <label for="contact_fonction" class="block text-sm font-medium text-gray-700">
                      Fonction du contact
                    </label>
                    <input
                      id="contact_fonction"
                      v-model="form.contact_fonction"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: Responsable maintenance"
                    />
                    <div v-if="form.errors.contact_fonction" class="mt-1 text-sm text-red-600">
                      {{ form.errors.contact_fonction }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notes et observations -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Notes et observations</h3>
                <div>
                  <label for="notes" class="block text-sm font-medium text-gray-700">
                    Notes particulières
                  </label>
                  <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Informations importantes, consignes d'accès, particularités du site..."
                  ></textarea>
                  <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                    {{ form.errors.notes }}
                  </div>
                </div>
              </div>

              <!-- Contacts du site -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Contacts du site</h3>
                <div v-if="form.contacts.length === 0" class="text-gray-500 mb-4">Aucun contact ajouté</div>
                <div v-for="(contact, index) in form.contacts" :key="index" class="border rounded-lg p-4 mb-4">
                  <div class="flex justify-between items-center mb-2">
                    <h4 class="font-medium text-gray-800">Contact {{ index + 1 }}</h4>
                    <Button variant="outline" size="sm" @click="removeContact(index)">
                      <Icon name="Trash" class="h-4 w-4" />
                    </Button>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <Label :for="`nom-${index}`">Nom *</Label>
                      <Input :id="`nom-${index}`" v-model="contact.nom" type="text" required />
                    </div>
                    <div>
                      <Label :for="`prenom-${index}`">Prénom *</Label>
                      <Input :id="`prenom-${index}`" v-model="contact.prenom" type="text" required />
                    </div>
                    <div>
                      <Label :for="`fonction-${index}`">Fonction</Label>
                      <Input :id="`fonction-${index}`" v-model="contact.fonction" type="text" />
                    </div>
                    <div>
                      <Label :for="`telephone-${index}`">Téléphone</Label>
                      <Input :id="`telephone-${index}`" v-model="contact.telephone" type="tel" />
                    </div>
                    <div class="md:col-span-2">
                      <Label :for="`email-${index}`">Email *</Label>
                      <Input :id="`email-${index}`" v-model="contact.email" type="email" required />
                    </div>
                  </div>
                </div>
                <Button variant="outline" size="sm" @click="addContact">
                  <Icon name="Plus" class="h-4 w-4 mr-2" /> Ajouter un contact
                </Button>
              </div>

              <!-- Boutons d'action -->
              <div class="flex items-center justify-end space-x-4 pt-6">
                <Link
                  :href="route('sites.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="form.processing">Création en cours...</span>
                  <span v-else>Créer le site</span>
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
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'

interface Client {
  id: number
  nom: string
}

const props = defineProps<{
  clients: Client[]
}>()

const form = useForm({
  nom: '',
  client_id: '',
  type_site: '',
  surface: '',
  adresse: '',
  code_postal: '',
  ville: '',
  pays: 'France',
  latitude: '',
  longitude: '',
  contact_nom: '',
  contact_telephone: '',
  contact_email: '',
  contact_fonction: '',
  notes: '',
  contacts: [] as Array<{ nom: string; prenom: string; fonction: string; telephone: string; email: string }>
})

if (form.contacts.length === 0) {
  form.contacts.push({ nom: '', prenom: '', fonction: '', telephone: '', email: '' })
}

const submit = () => {
  form.post(route('sites.store'))
}

const addContact = () => {
  form.contacts.push({ nom: '', prenom: '', fonction: '', telephone: '', email: '' })
}

const removeContact = (index: number) => {
  form.contacts.splice(index, 1)
}
</script> 