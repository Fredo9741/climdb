<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modifier la bouteille {{ bouteille.numero_serie }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="numero_serie" class="block text-sm font-medium text-gray-700">Numéro de série *</label>
                                    <input 
                                        type="text" 
                                        id="numero_serie" 
                                        v-model="form.numero_serie" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    />
                                    <div v-if="form.errors.numero_serie" class="mt-1 text-sm text-red-600">{{ form.errors.numero_serie }}</div>
                                </div>

                                <div>
                                    <label for="type_gaz_id" class="block text-sm font-medium text-gray-700">Type de gaz *</label>
                                    <select 
                                        id="type_gaz_id" 
                                        v-model="form.type_gaz_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="">Sélectionner un type</option>
                                        <option v-for="type in typesGaz" :key="type.id" :value="type.id">
                                            {{ type.nom }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.type_gaz_id" class="mt-1 text-sm text-red-600">{{ form.errors.type_gaz_id }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="capacite_kg" class="block text-sm font-medium text-gray-700">Capacité (kg) *</label>
                                    <input 
                                        type="number" 
                                        id="capacite_kg" 
                                        v-model="form.capacite_kg" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        min="0.1" max="100" step="0.1" required
                                    />
                                    <div v-if="form.errors.capacite_kg" class="mt-1 text-sm text-red-600">{{ form.errors.capacite_kg }}</div>
                                </div>

                                <div>
                                    <label for="poids_actuel_kg" class="block text-sm font-medium text-gray-700">Poids actuel (kg) *</label>
                                    <input 
                                        type="number" 
                                        id="poids_actuel_kg" 
                                        v-model="form.poids_actuel_kg" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        min="0" max="100" step="0.1" required
                                    />
                                    <div v-if="form.errors.poids_actuel_kg" class="mt-1 text-sm text-red-600">{{ form.errors.poids_actuel_kg }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="statut_bouteille_id" class="block text-sm font-medium text-gray-700">Statut *</label>
                                    <select 
                                        id="statut_bouteille_id" 
                                        v-model="form.statut_bouteille_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="">Sélectionner un statut</option>
                                        <option v-for="statut in statuts" :key="statut.id" :value="statut.id">
                                            {{ statut.nom }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.statut_bouteille_id" class="mt-1 text-sm text-red-600">{{ form.errors.statut_bouteille_id }}</div>
                                </div>

                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Technicien assigné</label>
                                    <select 
                                        id="user_id" 
                                        v-model="form.user_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Aucun technicien</option>
                                        <option v-for="technicien in techniciens" :key="technicien.id" :value="technicien.id">
                                            {{ technicien.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.user_id" class="mt-1 text-sm text-red-600">{{ form.errors.user_id }}</div>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea 
                                    id="notes" 
                                    v-model="form.notes" 
                                    rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Notes et commentaires sur la bouteille..."
                                ></textarea>
                                <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">{{ form.errors.notes }}</div>
                            </div>

                            <div class="flex justify-between">
                                <Link :href="route('bouteilles-gaz.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Annuler
                                </Link>
                                
                                <button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50">
                                    {{ form.processing ? 'Modification...' : 'Modifier la bouteille' }}
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
    bouteille: Object,
    typesGaz: Array,
    statuts: Array,
    techniciens: Array,
})

const form = useForm({
    numero_serie: props.bouteille.numero_serie,
    type_gaz_id: props.bouteille.type_gaz_id,
    capacite_kg: props.bouteille.capacite_kg,
    poids_actuel_kg: props.bouteille.poids_actuel_kg,
    statut_bouteille_id: props.bouteille.statut_bouteille_id,
    user_id: props.bouteille.user_id || '',
    notes: props.bouteille.notes || ''
})

const submit = () => {
    form.put(route('bouteilles-gaz.update', props.bouteille.id))
}
</script>