<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Statistiques -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class='bx bx-line-chart text-red-600 text-3xl'></i>
                        <h3 class="ml-2 text-xl font-semibold text-gray-800">Vos Statistiques</h3>
                    </div>
                    <div class="text-gray-600">
                        <p class="mb-2">Dernière visite: {{ now()->format('d/m/Y') }}</p>
                        <p>Progression: 75%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="bg-red-600 h-2.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- Programme en cours -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class='bx bx-calendar-check text-red-600 text-3xl'></i>
                        <h3 class="ml-2 text-xl font-semibold text-gray-800">Programme en cours</h3>
                    </div>
                    <div class="text-gray-600">
                        <p class="mb-2">Programme fitness débutant</p>
                        <p>Jour 5/30</p>
                        <button class="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                            Voir le programme
                        </button>
                    </div>
                </div>

                <!-- Prochaine séance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class='bx bx-time-five text-red-600 text-3xl'></i>
                        <h3 class="ml-2 text-xl font-semibold text-gray-800">Prochaine séance</h3>
                    </div>
                    <div class="text-gray-600">
                        <p class="mb-2">Cardio & Renforcement</p>
                        <p>Demain à 10:00</p>
                        <button class="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>

            <!-- Graphique de progression -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <div class="flex items-center mb-4">
                    <i class='bx bx-bar-chart-alt-2 text-red-600 text-3xl'></i>
                    <h3 class="ml-2 text-xl font-semibold text-gray-800">Votre progression</h3>
                </div>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">Graphique de progression à venir</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
