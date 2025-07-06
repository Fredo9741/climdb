<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Créer un nouveau véhicule
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <!-- Messages de feedback -->
                <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ $page.props.flash.error }}
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulaire principal -->
                    <div class="lg:col-span-2">
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <form @submit.prevent="submit" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Marque -->
                                        <div>
                                            <label for="marque" class="block text-sm font-medium text-gray-700">Marque *</label>
                                            <input 
                                                type="text" 
                                                id="marque" 
                                                v-model="form.marque" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.marque }"
                                                placeholder="Ex: Renault"
                                                required
                                            />
                                            <div v-if="form.errors.marque" class="mt-1 text-sm text-red-600">{{ form.errors.marque }}</div>
                                        </div>

                                        <!-- Modèle -->
                                        <div>
                                            <label for="modele" class="block text-sm font-medium text-gray-700">Modèle *</label>
                                            <input 
                                                type="text" 
                                                id="modele" 
                                                v-model="form.modele" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.modele }"
                                                placeholder="Ex: Kangoo"
                                                required
                                            />
                                            <div v-if="form.errors.modele" class="mt-1 text-sm text-red-600">{{ form.errors.modele }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Immatriculation -->
                                        <div>
                                            <label for="immatriculation" class="block text-sm font-medium text-gray-700">Immatriculation *</label>
                                            <input 
                                                type="text" 
                                                id="immatriculation" 
                                                v-model="form.immatriculation" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.immatriculation }"
                                                placeholder="Ex: AB-123-CD"
                                                required
                                            />
                                            <div v-if="form.errors.immatriculation" class="mt-1 text-sm text-red-600">{{ form.errors.immatriculation }}</div>
                                        </div>

                                        <!-- Année de fabrication -->
                                        <div>
                                            <label for="annee_fabrication" class="block text-sm font-medium text-gray-700">Année de fabrication *</label>
                                            <input 
                                                type="number" 
                                                id="annee_fabrication" 
                                                v-model="form.annee_fabrication" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.annee_fabrication }"
                                                :min="1900"
                                                :max="new Date().getFullYear()"
                                                required
                                            />
                                            <div v-if="form.errors.annee_fabrication" class="mt-1 text-sm text-red-600">{{ form.errors.annee_fabrication }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Kilométrage actuel -->
                                        <div>
                                            <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700">Kilométrage actuel *</label>
                                            <input 
                                                type="number" 
                                                id="kilometrage_actuel" 
                                                v-model="form.kilometrage_actuel" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.kilometrage_actuel }"
                                                min="0"
                                                step="1"
                                                required
                                            />
                                            <div v-if="form.errors.kilometrage_actuel" class="mt-1 text-sm text-red-600">{{ form.errors.kilometrage_actuel }}</div>
                                        </div>

                                        <!-- Type de carburant -->
                                        <div>
                                            <label for="type_carburant" class="block text-sm font-medium text-gray-700">Type de carburant *</label>
                                            <select 
                                                id="type_carburant" 
                                                v-model="form.type_carburant" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.type_carburant }"
                                                required
                                            >
                                                <option value="">Sélectionner un type</option>
                                                <option value="Essence">Essence</option>
                                                <option value="Diesel">Diesel</option>
                                                <option value="Électrique">Électrique</option>
                                                <option value="Hybride">Hybride</option>
                                                <option value="GPL">GPL</option>
                                            </select>
                                            <div v-if="form.errors.type_carburant" class="mt-1 text-sm text-red-600">{{ form.errors.type_carburant }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Date d'acquisition -->
                                        <div>
                                            <label for="date_acquisition" class="block text-sm font-medium text-gray-700">Date d'acquisition *</label>
                                            <input 
                                                type="date" 
                                                id="date_acquisition" 
                                                v-model="form.date_acquisition" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.date_acquisition }"
                                                required
                                            />
                                            <div v-if="form.errors.date_acquisition" class="mt-1 text-sm text-red-600">{{ form.errors.date_acquisition }}</div>
                                        </div>

                                        <!-- Statut -->
                                        <div>
                                            <label for="statut_vehicule_id" class="block text-sm font-medium text-gray-700">Statut *</label>
                                            <select 
                                                id="statut_vehicule_id" 
                                                v-model="form.statut_vehicule_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                :class="{ 'border-red-500': form.errors.statut_vehicule_id }"
                                                required
                                            >
                                                <option value="">Sélectionner un statut</option>
                                                <option v-for="statut in statuts" :key="statut.id" :value="statut.id">
                                                    {{ statut.nom }}
                                                </option>
                                            </select>
                                            <div v-if="form.errors.statut_vehicule_id" class="mt-1 text-sm text-red-600">{{ form.errors.statut_vehicule_id }}</div>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea 
                                            id="notes" 
                                            v-model="form.notes" 
                                            rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            :class="{ 'border-red-500': form.errors.notes }"
                                            placeholder="Notes et commentaires sur le véhicule..."
                                        ></textarea>
                                        <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex justify-between">
                                        <Link
                                            :href="route('vehicules.index')"
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Annuler
                                        </Link>
                                        
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                        >
                                            {{ form.processing ? 'Création...' : 'Créer le véhicule' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Gestion des affectations -->
                    <div class="space-y-6">
                        <!-- Affectations -->
                        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Affectations</h3>
                                    <button
                                        @click="ajouterAffectation"
                                        type="button"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200"
                                    >
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Ajouter
                                    </button>
                                </div>

                                <div v-if="form.affectations.length > 0" class="space-y-4">
                                    <div v-for="(affectation, index) in form.affectations" :key="index" 
                                         class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                Affectation #{{ index + 1 }}
                                            </h4>
                                            <button
                                                @click="supprimerAffectation(index)"
                                                type="button"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Technicien *</label>
                                                <select 
                                                    v-model="affectation.user_id" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required
                                                >
                                                    <option value="">Sélectionner un technicien</option>
                                                    <option v-for="technicien in techniciens" :key="technicien.id" :value="technicien.id">
                                                        {{ technicien.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Date de début *</label>
                                                <input 
                                                    type="date" 
                                                    v-model="affectation.date_debut" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required
                                                />
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Motif *</label>
                                                <input 
                                                    type="text" 
                                                    v-model="affectation.motif" 
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="Raison de l'affectation..."
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="text-center py-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Aucune affectation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    statuts: Array,
    techniciens: Array,
})

const form = useForm({
    marque: '',
    modele: '',
    immatriculation: '',
    annee_fabrication: new Date().getFullYear(),
    kilometrage_actuel: 0,
    type_carburant: '',
    date_acquisition: new Date().toISOString().split('T')[0],
    statut_vehicule_id: '',
    notes: '',
    affectations: []
})

const ajouterAffectation = () => {
    form.affectations.push({
        user_id: '',
        date_debut: '',
        motif: ''
    })
}

const supprimerAffectation = (index) => {
    form.affectations.splice(index, 1)
}

const submit = () => {
    form.post(route('vehicules.store'))
}
</script>