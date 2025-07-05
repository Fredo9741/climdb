<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modifier le véhicule {{ vehicule.marque }} {{ vehicule.modele }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="marque" class="block text-sm font-medium text-gray-700">Marque *</label>
                                    <input 
                                        type="text" 
                                        id="marque" 
                                        v-model="form.marque" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.marque }"
                                        required
                                    />
                                    <div v-if="form.errors.marque" class="mt-1 text-sm text-red-600">{{ form.errors.marque }}</div>
                                </div>

                                <div>
                                    <label for="modele" class="block text-sm font-medium text-gray-700">Modèle *</label>
                                    <input 
                                        type="text" 
                                        id="modele" 
                                        v-model="form.modele" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.modele }"
                                        required
                                    />
                                    <div v-if="form.errors.modele" class="mt-1 text-sm text-red-600">{{ form.errors.modele }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="immatriculation" class="block text-sm font-medium text-gray-700">Immatriculation *</label>
                                    <input 
                                        type="text" 
                                        id="immatriculation" 
                                        v-model="form.immatriculation" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.immatriculation }"
                                        required
                                    />
                                    <div v-if="form.errors.immatriculation" class="mt-1 text-sm text-red-600">{{ form.errors.immatriculation }}</div>
                                </div>

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
                                    {{ form.processing ? 'Modification...' : 'Modifier le véhicule' }}
                                </button>
                            </div>
                        </form>
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
    vehicule: Object,
    statuts: Array,
})

const form = useForm({
    marque: props.vehicule.marque,
    modele: props.vehicule.modele,
    immatriculation: props.vehicule.immatriculation,
    annee_fabrication: props.vehicule.annee_fabrication,
    kilometrage_actuel: props.vehicule.kilometrage_actuel,
    type_carburant: props.vehicule.type_carburant,
    date_acquisition: props.vehicule.date_acquisition,
    statut_vehicule_id: props.vehicule.statut_vehicule_id,
    notes: props.vehicule.notes || ''
})

const submit = () => {
    form.put(route('vehicules.update', props.vehicule.id))
}
</script>