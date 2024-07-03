@props(['exam'])

<div class="mt-4">
    <h4>Visualizar Prova</h4>
    <p><strong>Título:</strong> {{ $exam->title }}</p>
    <p><strong>Data:</strong> {{ $exam->date ? $exam->date->format('d/m/Y') : 'Não informada' }}</p>
    <p><strong>Descrição:</strong> {{ $exam->description ?? 'Não informada' }}</p>
    <p><strong>Concurso:</strong> {{ $exam->examination->title }}</p>
</div>
