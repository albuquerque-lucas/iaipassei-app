@props(['exam', 'numQuestions'])

<div class="mt-4">
    <h4 class="mb-4">Visualizar Prova</h4>
    <p><strong>Id:</strong> {{ $exam->id }}</p>
    <p><strong>Título:</strong> {{ $exam->title }}</p>
    <p><strong>Data:</strong> {{ $exam->date ? $exam->date->format('d/m/Y') : 'Não informada' }}</p>
    <p><strong>Descrição:</strong> {{ $exam->description ?? 'Não informada' }}</p>
    <p><strong>Concurso:</strong> {{ $exam->examination->title }}</p>
    <p><strong>Nº de Questões:</strong> {{ $numQuestions }}</p>
</div>
