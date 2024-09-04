@props(['exam', 'numQuestions', 'educationLevels'])

<div x-data="{ editing: null, value: '' }" class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Dados</h4>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <!-- Título -->
            <li class="list-group-item" @dblclick="editing = 'title'; value = '{{ $exam->title }}'">
                <template x-if="editing !== 'title'">
                    <span><strong>Título:</strong> {{ $exam->title }}</span>
                </template>
                <template x-if="editing === 'title'">
                    <form method="POST" action="{{ route('admin.exams.update', $exam->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="hidden" name="title" :value="value">
                            <input type="text" class="form-control" x-model="value">
                            <div class="btn-group ms-2">
                                <button type="submit" class="btn btn-sm btn-success rounded-0">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rounded-0" @click="editing = null">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </template>
            </li>

            <!-- Data -->
            <li class="list-group-item" @dblclick="editing = 'date'; value = '{{ $exam->date ? $exam->date->format('Y-m-d') : '' }}'">
                <template x-if="editing !== 'date'">
                    <span><strong>Data:</strong> {{ $exam->date ? $exam->date->format('d/m/Y') : 'Não informada' }}</span>
                </template>
                <template x-if="editing === 'date'">
                    <form method="POST" action="{{ route('admin.exams.update', $exam->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="date" class="form-control" name="date" x-model="value">
                            <div class="btn-group ms-2 rounded-0">
                                <button type="submit" class="btn btn-sm btn-success rounded-0">
                                    <i class="fa-regular fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rounded-0" @click="editing = null">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </template>
            </li>

            <!-- Escolaridade -->
            <li class="list-group-item" @dblclick="editing = 'educationLevel'; value = '{{ $exam->educationLevel->id ?? '' }}'">
                <template x-if="editing !== 'educationLevel'">
                    <span><strong>Escolaridade:</strong> {{ $exam->educationLevel->name ?? 'Não informado' }}</span>
                </template>
                <template x-if="editing === 'educationLevel'">
                    <form method="POST" action="{{ route('admin.exams.update', $exam->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-items-center">
                            <select class="form-select" name="education_level_id" x-model="value">
                                @foreach($educationLevels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                            <div class="btn-group ms-2 rounded-0">
                                <button type="submit" class="btn btn-sm btn-success rounded-0">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rounded-0" @click="editing = null">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </template>
            </li>

            <!-- Descrição -->
            <li class="list-group-item" @dblclick="editing = 'description'; value = '{{ $exam->description ?? '' }}'">
                <template x-if="editing !== 'description'">
                    <span><strong>Descrição:</strong> {{ $exam->description ?? 'Não informada' }}</span>
                </template>
                <template x-if="editing === 'description'">
                    <form method="POST" action="{{ route('admin.exams.update', $exam->slug) }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="text" class="form-control" name="description" x-model="value">
                            <div class="btn-group ms-2 rounded-0">
                                <button type="submit" class="btn btn-sm btn-success rounded-0">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rounded-0" @click="editing = null">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </template>
            </li>

            <!-- Concurso -->
            <li class="list-group-item">
                <strong>Concurso:</strong> {{ $exam->examination->title }}
            </li>

            <!-- Nº de Questões -->
            <li class="list-group-item">
                <strong>Nº de Questões:</strong> {{ $numQuestions }}
            </li>
        </ul>
    </div>
</div>
