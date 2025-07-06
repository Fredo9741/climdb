<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Nouvelle Facture</h2>
                <p class="text-gray-600">Créer une nouvelle facture client</p>
              </div>
              <Link
                :href="route('factures.index')"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
              >
                Retour
              </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
              <!-- Informations de base -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informations de base</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Numéro de facture -->
                  <div>
                    <label for="numero" class="block text-sm font-medium text-gray-700">
                      Numéro de facture *
                    </label>
                    <input
                      id="numero_facture"
                      v-model="form.numero_facture"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Ex: FAC-2024-001"
                      required
                    />
                    <div v-if="form.errors.numero_facture" class="mt-1 text-sm text-red-600">
                      {{ form.errors.numero_facture }}
                    </div>
                  </div>

                  <!-- Date de facture -->
                  <div>
                    <label for="date_facture" class="block text-sm font-medium text-gray-700">
                      Date de facture *
                    </label>
                    <input
                      id="date_facture"
                      v-model="form.date_facture"
                      type="date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      required
                    />
                    <div v-if="form.errors.date_facture" class="mt-1 text-sm text-red-600">
                      {{ form.errors.date_facture }}
                    </div>
                  </div>

                  <!-- Date d'échéance -->
                  <div>
                    <label for="date_echeance" class="block text-sm font-medium text-gray-700">
                      Date d'échéance
                    </label>
                    <input
                      id="date_echeance"
                      v-model="form.date_echeance"
                      type="date"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <div v-if="form.errors.date_echeance" class="mt-1 text-sm text-red-600">
                      {{ form.errors.date_echeance }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sélection du client -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Client</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Client -->
                  <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">
                      Client facturé *
                    </label>
                    <select
                      id="client_id"
                      v-model="form.client_id"
                      @change="onClientChange"
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

                  <!-- Type de facturation -->
                  <div>
                    <label for="type_facturation" class="block text-sm font-medium text-gray-700">
                      Type de facturation
                    </label>
                    <select
                      id="type_facturation"
                      v-model="form.type_facturation"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="intervention">Intervention</option>
                      <option value="devis">Devis</option>
                      <option value="maintenance">Contrat de maintenance</option>
                      <option value="piece">Vente de pièces</option>
                      <option value="autre">Autre</option>
                    </select>
                    <div v-if="form.errors.type_facturation" class="mt-1 text-sm text-red-600">
                      {{ form.errors.type_facturation }}
                    </div>
                  </div>
                </div>

                <!-- Adresse de facturation -->
                <div v-if="selectedClient" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                  <h4 class="font-medium text-blue-800 mb-2">Adresse de facturation</h4>
                  <div class="text-sm text-blue-700">
                    <div>{{ selectedClient.nom }}</div>
                    <div>{{ selectedClient.adresse }}</div>
                    <div>{{ selectedClient.code_postal }} {{ selectedClient.ville }}</div>
                    <div>{{ selectedClient.pays }}</div>
                  </div>
                </div>
              </div>

              <!-- Articles/Services -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-semibold text-gray-800">Articles et services</h3>
                  <button
                    type="button"
                    @click="addLigne"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
                  >
                    + Ajouter une ligne
                  </button>
                </div>

                <div class="space-y-3">
                                     <div
                     v-for="(ligne, index) in (form.lignes as any)"
                     :key="index"
                     class="grid grid-cols-12 gap-2 items-start bg-white p-3 rounded border"
                   >
                    <!-- Description -->
                    <div class="col-span-5">
                      <label class="block text-xs font-medium text-gray-700">Description</label>
                      <textarea
                        v-model="ligne.description"
                        rows="2"
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Description du service ou produit"
                      ></textarea>
                    </div>

                    <!-- Quantité -->
                    <div class="col-span-1">
                      <label class="block text-xs font-medium text-gray-700">Qté</label>
                      <input
                        v-model="ligne.quantite"
                        type="number"
                        step="0.01"
                        min="0"
                        @input="calculateLigne(index)"
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      />
                    </div>

                    <!-- Unité -->
                    <div class="col-span-1">
                      <label class="block text-xs font-medium text-gray-700">Unité</label>
                      <select
                        v-model="ligne.unite"
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      >
                        <option value="h">h</option>
                        <option value="u">u</option>
                        <option value="m">m</option>
                        <option value="m²">m²</option>
                        <option value="kg">kg</option>
                        <option value="l">l</option>
                      </select>
                    </div>

                    <!-- Prix unitaire -->
                    <div class="col-span-2">
                      <label class="block text-xs font-medium text-gray-700">Prix unitaire HT</label>
                      <input
                        v-model="ligne.prix_unitaire"
                        type="number"
                        step="0.01"
                        min="0"
                        @input="calculateLigne(index)"
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="0.00"
                      />
                    </div>

                    <!-- TVA -->
                    <div class="col-span-1">
                      <label class="block text-xs font-medium text-gray-700">TVA %</label>
                      <select
                        v-model="ligne.taux_tva"
                        @change="calculateLigne(index)"
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      >
                        <option value="0">0%</option>
                        <option value="5.5">5.5%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                      </select>
                    </div>

                    <!-- Total HT -->
                    <div class="col-span-1">
                      <label class="block text-xs font-medium text-gray-700">Total HT</label>
                      <input
                        :value="(ligne.quantite * ligne.prix_unitaire).toFixed(2)"
                        readonly
                        class="mt-1 block w-full text-sm rounded-md border-gray-300 bg-gray-50 text-gray-600"
                      />
                    </div>

                    <!-- Supprimer -->
                    <div class="col-span-1 flex justify-center items-end">
                      <button
                        type="button"
                        @click="removeLigne(index)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs"
                        :disabled="form.lignes.length <= 1"
                      >
                        ×
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Totaux -->
                <div class="mt-6 border-t pt-4">
                  <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                      <div class="flex justify-between text-sm">
                        <span class="font-medium">Total HT :</span>
                        <span>{{ totalHT.toFixed(2) }} €</span>
                      </div>
                      <div class="flex justify-between text-sm">
                        <span class="font-medium">Total TVA :</span>
                        <span>{{ totalTVA.toFixed(2) }} €</span>
                      </div>
                      <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>Total TTC :</span>
                        <span>{{ totalTTC.toFixed(2) }} €</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Notes et conditions -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Notes et conditions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Notes -->
                  <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                      Notes internes
                    </label>
                    <textarea
                      id="notes"
                      v-model="form.notes"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Notes visibles sur la facture..."
                    ></textarea>
                    <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                      {{ form.errors.notes }}
                    </div>
                  </div>

                  <!-- Conditions de paiement -->
                  <div>
                    <label for="conditions_paiement" class="block text-sm font-medium text-gray-700">
                      Conditions de paiement
                    </label>
                    <select
                      id="conditions_paiement"
                      v-model="form.conditions_paiement"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                      <option value="comptant">Comptant</option>
                      <option value="30_jours">30 jours</option>
                      <option value="45_jours">45 jours</option>
                      <option value="60_jours">60 jours</option>
                      <option value="fin_mois">Fin de mois</option>
                    </select>
                    <div v-if="form.errors.conditions_paiement" class="mt-1 text-sm text-red-600">
                      {{ form.errors.conditions_paiement }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Statut -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Statut de la facture</h3>
                <div>
                  <label for="statut_paiement" class="block text-sm font-medium text-gray-700">
                    Statut de paiement
                  </label>
                  <select
                    id="statut_paiement"
                    v-model="form.statut_paiement"
                    class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  >
                    <option value="en_attente">En attente</option>
                    <option value="paye">Payée</option>
                    <option value="en_retard">En retard</option>
                    <option value="partiellement_paye">Partiellement payée</option>
                    <option value="annule">Annulée</option>
                  </select>
                  <div v-if="form.errors.statut_paiement" class="mt-1 text-sm text-red-600">
                    {{ form.errors.statut_paiement }}
                  </div>
                </div>
              </div>

              <!-- Boutons d'action -->
              <div class="flex items-center justify-end space-x-4 pt-6">
                <Link
                  :href="route('factures.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded"
                >
                  Annuler
                </Link>
                <button
                  type="button"
                  @click="saveDraft"
                  :disabled="form.processing"
                  class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  Sauvegarder en brouillon
                </button>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="form.processing">Création en cours...</span>
                  <span v-else>Créer la facture</span>
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
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

interface Client {
  id: number
  nom: string
  adresse: string
  code_postal: string
  ville: string
  pays: string
}

interface LigneFacture {
  [key: string]: FormDataConvertible
  description: string
  quantite: number
  unite: string
  prix_unitaire: number
  taux_tva: number
}

const props = defineProps<{
  clients: Client[]
}>()

const selectedClient = ref<Client | null>(null)

const form = useForm({
  numero_facture: '',
  date_facture: new Date().toISOString().split('T')[0],
  date_echeance: '',
  client_id: '',
  description: '',
  statut: 'emise',
  conditions_paiement: '30_jours',
  lignes: [
    {
      description: '',
      quantite: 1,
      unite: 'h',
      prix_unitaire: 0,
      taux_tva: 20
    }
  ] as LigneFacture[]
})

// Calculs automatiques
const totalHT = computed(() => {
  return form.lignes.reduce((total, ligne) => {
    return total + (ligne.quantite * ligne.prix_unitaire)
  }, 0)
})

const totalTVA = computed(() => {
  return form.lignes.reduce((total, ligne) => {
    const montantHT = ligne.quantite * ligne.prix_unitaire
    return total + (montantHT * ligne.taux_tva / 100)
  }, 0)
})

const totalTTC = computed(() => {
  return totalHT.value + totalTVA.value
})

// Gestion des lignes
const addLigne = () => {
  form.lignes.push({
    description: '',
    quantite: 1,
    unite: 'h',
    prix_unitaire: 0,
    taux_tva: 20
  })
}

const removeLigne = (index: number) => {
  if (form.lignes.length > 1) {
    form.lignes.splice(index, 1)
  }
}

const calculateLigne = (index: number) => {
  // Forcer le recalcul des totaux
  const ligne = form.lignes[index]
  if (ligne.quantite < 0) ligne.quantite = 0
  if (ligne.prix_unitaire < 0) ligne.prix_unitaire = 0
}

// Gestion du client
const onClientChange = () => {
  selectedClient.value = props.clients.find(c => c.id === parseInt(form.client_id)) || null
  
  // Calculer automatiquement la date d'échéance selon les conditions
  if (form.date_facture && form.conditions_paiement) {
    const dateFacture = new Date(form.date_facture)
    let joursEcheance = 0
    
    switch (form.conditions_paiement) {
      case '30_jours':
        joursEcheance = 30
        break
      case '45_jours':
        joursEcheance = 45
        break
      case '60_jours':
        joursEcheance = 60
        break
      case 'fin_mois':
        joursEcheance = 30
        break
      default:
        joursEcheance = 0
    }
    
    if (joursEcheance > 0) {
      const dateEcheance = new Date(dateFacture)
      dateEcheance.setDate(dateEcheance.getDate() + joursEcheance)
      form.date_echeance = dateEcheance.toISOString().split('T')[0]
    }
  }
}

// Surveiller les changements de conditions de paiement
watch(() => form.conditions_paiement, () => {
  onClientChange()
})

const submit = () => {
  form.transform((data) => ({
    ...data,
    montant_ht: totalHT.value,
    montant_tva: totalTVA.value,
    montant_ttc: totalTTC.value
  })).post(route('factures.store'))
}

const saveDraft = () => {
  form.statut_paiement = 'brouillon'
  submit()
}

// Générer automatiquement un numéro de facture
const generateNumero = () => {
  const year = new Date().getFullYear()
  const month = String(new Date().getMonth() + 1).padStart(2, '0')
  const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')
  form.numero = `FAC-${year}${month}-${random}`
}

// Générer le numéro au chargement si vide
if (!form.numero) {
  generateNumero()
}
</script> 