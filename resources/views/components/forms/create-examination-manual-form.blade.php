<form method="POST" action="{{ route('admin.examinations.store') }}" id="examinationForm" enctype="multipart/form-data" x-data="examinationFormHandler()">
    @csrf
    <div id="loadingBar" class="progress" x-show="isSubmitting" style="height: 5px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;"></div>
    </div>
    <div class="mb-3">
        <label for="education_level_id" class="form-label">Nível Educacional</label>
        <select class="form-select" id="education_level_id" name="education_level_id" required x-model="formData.education_level_id">
            @foreach($educationLevels as $level)
                <option value="{{ $level->id }}">{{ $level->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Título</label>
        <input type="text" class="form-control" id="title" name="title" required x-model="formData.title">
    </div>
    <div class="mb-3">
        <label for="institution" class="form-label">Instituição</label>
        <input type="text" class="form-control" id="institution" name="institution" required x-model="formData.institution">
    </div>
    <div class="mb-3">
        <label for="num_exams" class="form-label">Número de Provas</label>
        <select class="form-select" id="num_exams" name="num_exams" required x-model="formData.num_exams">
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="mb-3">
        <label for="num_questions_per_exam" class="form-label">Número de Questões por Prova</label>
        <input type="number" class="form-control" id="num_questions_per_exam" name="num_questions_per_exam" required x-model="formData.num_questions_per_exam">
    </div>
    <div class="mb-3">
        <label for="num_alternatives_per_question" class="form-label">Número de Alternativas por Questão</label>
        <input type="number" class="form-control" id="num_alternatives_per_question" name="num_alternatives_per_question" required x-model="formData.num_alternatives_per_question">
    </div>
    <div class="mb-3">
        <label for="notice" class="form-label">Edital do Concurso</label>
        <input type="file" class="form-control" id="notice" name="notice" @change="handleFileInput">
    </div>
    <button type="submit" class="btn btn-dark" @click="submitForm">Criar</button>
</form>

<script>
    function examinationFormHandler() {
        return {
            isSubmitting: false,
            formData: {
                education_level_id: '',
                title: '',
                institution: '',
                num_exams: 1,
                num_questions_per_exam: '',
                num_alternatives_per_question: ''
            },
            handleFileInput(event) {
                const file = event.target.files[0];
                if (file) {
                    this.uploadFile(file);
                }
            },
            uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);

                fetch('{{ route('admin.examinations.import') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    this.formData.education_level_id = data.education_level_id;
                    this.formData.title = data.title;
                    this.formData.institution = data.institution;
                    this.formData.num_exams = data.num_exams;
                    this.formData.num_questions_per_exam = data.num_questions_per_exam;
                    this.formData.num_alternatives_per_question = data.num_alternatives_per_question;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            },
            submitForm(event) {
                this.isSubmitting = true;
                document.getElementById('examinationForm').submit();
            }
        }
    }
</script>
