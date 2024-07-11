<form method="POST" action="{{ route('admin.examinations.import') }}" enctype="multipart/form-data" x-data="fileUploadHandler"
    class="d-flex flex-column justify-content-center align-items-center mt-5"
    style="height: 200px; border: 2px dashed #333; border-radius: 10px; cursor: pointer;"
    @click="openFilePicker"
    @dragover.prevent="isDragging = true"
    @dragleave.prevent="isDragging = false"
    @drop.prevent="handleDrop($event)">

    @csrf

    <div class="text-center">
        <i class="fas fa-plus-circle fa-3x text-muted" x-show="!fileName"></i>
        <p class="text-muted mt-2" x-text="fileName ? fileName : 'Arraste e solte o arquivo PDF aqui ou clique para fazer upload'"></p>
    </div>
    <input type="file" x-ref="fileInput" id="fileInput" name="file" accept="application/pdf" style="display: none;" @change="handleFileInput">
    <button type="button" class="btn btn-danger btn-sm mt-3" @click.stop="clearFileInput" x-show="fileName">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <button type="submit" class="btn btn-primary btn-sm mt-3" x-show="fileName">
        Enviar
    </button>
</form>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUploadHandler', () => ({
            fileName: '',
            isDragging: false,
            openFilePicker() {
                this.$refs.fileInput.click();
            },
            handleDrop(event) {
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    this.uploadFile(files[0]);
                }
            },
            handleFileInput(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    this.uploadFile(files[0]);
                }
            },
            uploadFile(file) {
                this.fileName = file.name;
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
                    document.getElementById('education_level_id').value = data.education_level_id;
                    document.getElementById('title').value = data.title;
                    document.getElementById('institution').value = data.institution;
                    document.getElementById('num_exams').value = data.num_exams;
                    document.getElementById('num_questions_per_exam').value = data.num_questions_per_exam;
                    document.getElementById('num_alternatives_per_question').value = data.num_alternatives_per_question;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            },
            clearFileInput() {
                this.fileName = '';
                this.$refs.fileInput.value = '';
            }
        }));
    });
</script>
