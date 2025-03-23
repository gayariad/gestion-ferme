@extends('layouts.app')

@section('title', 'Attribution des tâches - Woofer')

@section('content')
<div class="container my-5" x-data="calendar({{ $woofer->taches->toJson() }})">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Colonne de gauche : Informations du woofer -->
        <div class="md:w-1/3 bg-yellow-100 p-4 rounded shadow">
            <div class="flex flex-col items-center">
                <!-- Image (placeholder) -->
                <div class="mb-4">
                    <img src="https://via.placeholder.com/150" alt="Photo du woofer" class="rounded-full">
                </div>
                <!-- Infos basiques -->
                <h2 class="text-xl font-bold mb-2">Prénom : {{ $woofer->personne->prenom }}</h2>
                <h3 class="text-xl font-bold mb-4">Nom : {{ $woofer->personne->nom }}</h3>
                <p class="text-sm"><strong>Email :</strong> {{ $woofer->personne->mail }}</p>
                <p class="text-sm"><strong>Adresse :</strong> {{ $woofer->personne->adresse }}</p>
                <p class="text-sm"><strong>Durée :</strong> du {{ $woofer->debut_sejour }} au {{ $woofer->fin_sejour }}</p>
                <hr class="my-4">
                <p class="text-sm font-bold">Diplômes/Formations :</p>
                <p class="text-sm">{{ $woofer->competence }}</p>
            </div>
        </div>

        <!-- Colonne de droite : Calendrier et modal d'attribution de tâche -->
        <div class="md:w-2/3 bg-white p-4 rounded shadow">
            <!-- Navigation du calendrier -->
            <div class="flex justify-between items-center mb-4">
                <button @click="prevMonth()" class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded">Précédent</button>
                <div class="text-xl font-bold" x-text="monthYear"></div>
                <button @click="nextMonth()" class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded">Suivant</button>
            </div>

            <!-- Calendrier dynamique -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-center border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">Lun</th>
                            <th class="border p-2">Mar</th>
                            <th class="border p-2">Mer</th>
                            <th class="border p-2">Jeu</th>
                            <th class="border p-2">Ven</th>
                            <th class="border p-2">Sam</th>
                            <th class="border p-2">Dim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="week in weeks" :key="week[0].date">
                            <tr>
                                <template x-for="day in week" :key="day.date">
                                    <td class="border p-2 h-20 align-top">
                                        <div class="text-sm font-bold mb-1" x-text="day.day"></div>
                                        <template x-for="task in day.tasks" :key="task.id_tache">
                                            <div class="text-xs bg-blue-100 rounded p-1 my-1">
                                                <span x-text="task.nom_tache"></span>
                                                <span x-show="task.temps_estime" class="ml-1 text-gray-700" x-text="'(' + task.temps_estime + ' h)'"></span>
                                            </div>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Bouton pour ouvrir le modal d'attribution de tâche -->
            <div class="mt-6">
                <button @click="openModal = true" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Attribuer une nouvelle tâche
                </button>
            </div>

            <!-- Modal d'attribution de tâche -->
            <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" style="display: none;">
                <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Attribuer une tâche</h3>
                        <button type="button" @click="openModal = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
                    </div>
                    <form action="{{ route('taches.store') }}" method="POST">
                        @csrf
                        <!-- Champ caché pour lier la tâche au woofer -->
                        <input type="hidden" name="id_woofer" value="{{ $woofer->id_woofer }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nom de la tâche</label>
                            <input type="text" name="nom_tache" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Date de la tâche</label>
                            <input type="date" name="date_tache" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Temps estimé (en heures)</label>
                            <input type="number" name="temps_estime" step="0.5" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="openModal = false" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                                Annuler
                            </button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AlpineJS Calendar Component -->
<script>
function calendar(tasks) {
    return {
        currentDate: new Date(),
        tasks: tasks, // Tâches du woofer en JSON
        openModal: false,
        get monthYear() {
            return this.currentDate.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        },
        prevMonth() {
            let d = new Date(this.currentDate);
            d.setMonth(d.getMonth() - 1);
            this.currentDate = d;
        },
        nextMonth() {
            let d = new Date(this.currentDate);
            d.setMonth(d.getMonth() + 1);
            this.currentDate = d;
        },
        get weeks() {
            let weeks = [];
            let year = this.currentDate.getFullYear();
            let month = this.currentDate.getMonth();
            let firstDayOfMonth = new Date(year, month, 1);
            // Calculer le début de la semaine (lundi) pour le premier jour du mois
            let startDate = new Date(firstDayOfMonth);
            let dayOfWeek = startDate.getDay();
            dayOfWeek = dayOfWeek === 0 ? 7 : dayOfWeek; // considérer dimanche comme 7
            startDate.setDate(startDate.getDate() - (dayOfWeek - 1));

            // Générer 6 semaines (42 jours)
            for (let week = 0; week < 6; week++) {
                let weekArr = [];
                for (let day = 0; day < 7; day++) {
                    let currentDay = new Date(startDate);
                    currentDay.setDate(startDate.getDate() + week * 7 + day);
                    let dateStr = currentDay.toISOString().split('T')[0];
                    // Filtrer les tâches pour ce jour
                    let dayTasks = this.tasks.filter(task => task.date_tache === dateStr);
                    weekArr.push({
                        date: dateStr,
                        day: currentDay.getDate(),
                        tasks: dayTasks
                    });
                }
                weeks.push(weekArr);
            }
            return weeks;
        }
    }
}
</script>

@endsection
