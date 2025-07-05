<template>
    <AuthenticatedLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Nouveau Technicien</h1>
                        <p class="text-sm text-gray-600 mt-1">Créer un nouveau compte technicien</p>
                    </div>
                    <Link 
                        :href="route('techniciens.index')"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </Link>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.name }"
                                required
                            />
                            <div v-if="$page.props.errors.name" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                v-model="form.email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.email }"
                                required
                            />
                            <div v-if="$page.props.errors.email" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.email }}
                            </div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">
                                Téléphone
                            </label>
                            <input
                                type="tel"
                                id="telephone"
                                v-model="form.telephone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.telephone }"
                                placeholder="Ex: 01 23 45 67 89"
                            />
                            <div v-if="$page.props.errors.telephone" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.telephone }}
                            </div>
                        </div>

                        <!-- Spécialité -->
                        <div>
                            <label for="specialite" class="block text-sm font-medium text-gray-700">
                                Spécialité
                            </label>
                            <select
                                id="specialite"
                                v-model="form.specialite"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.specialite }"
                            >
                                <option value="">Sélectionnez une spécialité</option>
                                <option value="Climatisation">Climatisation</option>
                                <option value="Électricité">Électricité</option>
                                <option value="Plomberie">Plomberie</option>
                                <option value="Maintenance générale">Maintenance générale</option>
                                <option value="Télécommunications">Télécommunications</option>
                                <option value="Informatique">Informatique</option>
                                <option value="Mécanique">Mécanique</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <div v-if="$page.props.errors.specialite" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.specialite }}
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                v-model="form.password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.password }"
                                required
                                minlength="8"
                            />
                            <div v-if="$page.props.errors.password" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.password }}
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Minimum 8 caractères</p>
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmer le mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                :class="{ 'border-red-500': $page.props.errors.password_confirmation }"
                                required
                            />
                            <div v-if="$page.props.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                                {{ $page.props.errors.password_confirmation }}
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="actif"
                            v-model="form.actif"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="actif" class="ml-2 block text-sm text-gray-900">
                            Compte actif
                        </label>
                    </div>

                    <!-- Informations d'aide -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Informations importantes
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Le technicien recevra automatiquement le rôle "technicien"</li>
                                        <li>Il pourra se connecter avec son email et mot de passe</li>
                                        <li>Il aura accès aux fonctionnalités de gestion des interventions</li>
                                        <li>Vous pourrez lui affecter des véhicules et bouteilles de gaz</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3">
                        <Link 
                            :href="route('techniciens.index')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                        >
                            Annuler
                        </Link>
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                        >
                            <span v-if="form.processing">Création...</span>
                            <span v-else>Créer le technicien</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

export default {
    name: 'TechniciensCreate',
    components: {
        AuthenticatedLayout,
        Link,
    },
    setup() {
        const form = useForm({
            name: '',
            email: '',
            telephone: '',
            specialite: '',
            password: '',
            password_confirmation: '',
            actif: true,
        });

        const submit = () => {
            form.post(route('techniciens.store'));
        };

        return {
            form,
            submit,
        };
    },
};
</script>