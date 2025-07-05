<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modifier le mouvement du {{ formatDateTime(mouvement.date_mouvement) }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Équipement -->
                                <div>
                                    <label for="equipement_id" class="block text-sm font-medium text-gray-700">Équipement *</label>
                                    <select 
                                        id="equipement_id" 
                                        v-model="form.equipement_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.equipement_id }"
                                        required
                                    >
                                        <option value="">Sélectionner un équipement</option>
                                        <option v-for="equipement in equipements" :key="equipement.id" :value="equipement.id">
                                            {{ equipement.nom }} - {{ equipement.client?.nom || 'Client inconnu' }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.equipement_id" class="mt-1 text-sm text-red-600">{{ form.errors.equipement_id }}</div>
                                </div>

                                <!-- Type de gaz -->
                                <div>
                                    <label for="type_gaz_id" class="block text-sm font-medium text-gray-700">Type de gaz *</label>
                                    <select 
                                        id="type_gaz_id" 
                                        v-model="form.type_gaz_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.type_gaz_id }"
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
                                <!-- Type de mouvement -->
                                <div>
                                    <label for="type_mouvement" class="block text-sm font-medium text-gray-700">Type de mouvement *</label>
                                    <select 
                                        id="type_mouvement" 
                                        v-model="form.type_mouvement" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.type_mouvement }"
                                        required
                                    >
                                        <option value="">Sélectionner un type</option>
                                        <option value="entrée">Entrée (Remplissage)</option>
                                        <option value="sortie">Sortie (Consommation)</option>
                                    </select>
                                    <div v-if="form.errors.type_mouvement" class="mt-1 text-sm text-red-600">{{ form.errors.type_mouvement }}</div>
                                </div>

                                <!-- Quantité -->
                                <div>
                                    <label for="quantite_kg" class="block text-sm font-medium text-gray-700">Quantité (kg) *</label>
                                    <input 
                                        type="number" 
                                        id="quantite_kg" 
                                        v-model="form.quantite_kg" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.quantite_kg }"
                                        min="0.1"
                                        max="100"
                                        step="0.1"
                                        required
                                    />
                                    <div v-if="form.errors.quantite_kg" class="mt-1 text-sm text-red-600">{{ form.errors.quantite_kg }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Date du mouvement -->
                                <div>
                                    <label for="date_mouvement" class="block text-sm font-medium text-gray-700">Date du mouvement *</label>
                                    <input 
                                        type="datetime-local" 
                                        id="date_mouvement" 
                                        v-model="form.date_mouvement" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.date_mouvement }"
                                        required
                                    />
                                    <div v-if="form.errors.date_mouvement" class="mt-1 text-sm text-red-600">{{ form.errors.date_mouvement }}</div>
                                </div>

                                <!-- Technicien -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Technicien responsable *</label>
                                    <select 
                                        id="user_id" 
                                        v-model="form.user_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        :class="{ 'border-red-500': form.errors.user_id }"
                                        required
                                    >
                                        <option value="">Sélectionner un technicien</option>
                                        <option v-for="technicien in techniciens" :key="technicien.id" :value="technicien.id">
                                            {{ technicien.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.user_id" class="mt-1 text-sm text-red-600">{{ form.errors.user_id }}</div>
                                </div>
                            </div>

                            <!-- Intervention (optionnel) -->
                            <div>
                                <label for="intervention_id" class="block text-sm font-medium text-gray-700">Intervention associée (optionnel)</label>
                                <select 
                                    id="intervention_id" 
                                    v-model="form.intervention_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.intervention_id }"
                                >
                                    <option value="">Aucune intervention</option>
                                    <option v-for="intervention in interventions" :key="intervention.id" :value="intervention.id">
                                        #{{ intervention.id }} - {{ intervention.site?.nom || 'Site inconnu' }}
                                        ({{ formatDate(intervention.date_prevue) }})
                                    </option>
                                </select>
                                <div v-if="form.errors.intervention_id" class="mt-1 text-sm text-red-600">{{ form.errors.intervention_id }}</div>
                            </div>

                            <!-- Observations -->
                            <div>
                                <label for="observations" class="block text-sm font-medium text-gray-700">Observations</label>
                                <textarea 
                                    id="observations" 
                                    v-model="form.observations" 
                                    rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.observations }"
                                    placeholder="Détails sur le mouvement, conditions particulières, etc."
                                ></textarea>
                                <div v-if="form.errors.observations" class="mt-1 text-sm text-red-600">{{ form.errors.observations }}</div>
                            </div>

                            <!-- Aperçu du mouvement -->
                            <div v-if="form.type_mouvement && form.quantite_kg" class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Aperçu du mouvement</h4>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                          :class="form.type_mouvement === 'entrée' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                        {{ form.type_mouvement === 'entrée' ? 'ENTRÉE' : 'SORTIE' }}
                                    </span>
                                    <span class="text-sm font-medium"
                                          :class="form.type_mouvement === 'entrée' ? 'text-green-600' : 'text-red-600'">
                                        {{ form.type_mouvement === 'entrée' ? '+' : '-' }}{{ form.quantite_kg }} kg
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between">
                                <Link
                                    :href="route('mouvements-gaz.index')"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Annuler
                                </Link>
                                
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Modification...' : 'Modifier le mouvement' }}
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
    mouvement: Object,
    equipements: Array,
    typesGaz: Array,
    interventions: Array,
    techniciens: Array,
})

const form = useForm({
    equipement_id: props.mouvement.equipement_id,
    type_gaz_id: props.mouvement.type_gaz_id,
    intervention_id: props.mouvement.intervention_id || '',
    user_id: props.mouvement.user_id,
    type_mouvement: props.mouvement.type_mouvement,
    quantite_kg: props.mouvement.quantite_kg,
    date_mouvement: formatDateTimeLocal(props.mouvement.date_mouvement),
    observations: props.mouvement.observations || ''
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR')
}

const formatDateTime = (date) => {
    return new Date(date).toLocaleString('fr-FR')
}

const formatDateTimeLocal = (date) => {
    return new Date(date).toISOString().slice(0, 16)
}

const submit = () => {
    form.put(route('mouvements-gaz.update', props.mouvement.id))
}
</script>