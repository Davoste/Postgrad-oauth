@extends('layouts.app')

@section('title', 'Thesis Reviews - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">Thesis Reviews</h2>
                            <p class="text-muted mb-0">Review and manage thesis submissions</p>
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="statusFilter" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="revision_required">Revision Required</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Pending Reviews</h5>
                </div>
                <div class="card-body">
                    @if($pendingSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Thesis Title</th>
                                    <th>Stage</th>
                                    <th>Type</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingSubmissions as $submission)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $submission->thesis->student->user->name }}</strong><br>
                                            <small class="text-muted">{{ $submission->thesis->student->student_id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ Str::limit($submission->title, 50) }}</strong><br>
                                            <small class="text-muted">{{ $submission->thesis->supervisor ? $submission->thesis->supervisor->user->name : 'No Supervisor' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $submission->stage)) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $submission->submission_type)) }}</span>
                                    </td>
                                    <td>{{ $submission->submitted_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $submission->id }}">
                                                <i class="fas fa-eye"></i> Review
                                            </button>
                                            @if($submission->documents)
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-download"></i> Files
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach($submission->documents as $i => $doc)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('documents.download', [$submission, $i]) }}">
                                                            <i class="fas fa-file me-1"></i>{{ $doc['original_name'] }}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No Pending Reviews</h5>
                        <p class="text-muted">All thesis submissions have been reviewed.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modals -->
@foreach($pendingSubmissions as $submission)
<div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $submission->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel{{ $submission->id }}">Review Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.thesis.review', $submission) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Submission Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Submission Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td>{{ $submission->thesis->student->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $submission->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stage:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $submission->stage)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $submission->submission_type)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted:</strong></td>
                                    <td>{{ $submission->submitted_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Description</h6>
                            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                {{ $submission->description }}
                            </div>

                            @if($submission->documents)
                            <h6 class="mt-3">Documents</h6>
                            <ul class="list-group list-group-flush">
                                @foreach($submission->documents as $i => $doc)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <i class="fas fa-file me-1 text-muted"></i>
                                        <span class="small">{{ $doc['original_name'] }}</span>
                                        <small class="text-muted ms-2">{{ number_format($doc['size'] / 1024, 1) }} KB</small>
                                    </div>
                                    <a href="{{ route('documents.download', [$submission, $i]) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Review Form -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Decision <span class="text-danger">*</span></label>
                                <select class="form-select" name="decision" required>
                                    <option value="">Select decision</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                    <option value="revision_required">Revision Required</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating (1-5)</label>
                                <select class="form-select" name="rating">
                                    <option value="">Select rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Below Average</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_meeting" id="requiresMeeting{{ $submission->id }}">
                                    <label class="form-check-label" for="requiresMeeting{{ $submission->id }}">
                                        Requires follow-up meeting
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Comments <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="comments" rows="4" required
                                          placeholder="Provide detailed feedback on the submission"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Strengths</label>
                                <textarea class="form-control" name="strengths" rows="2"
                                          placeholder="What are the strengths of this submission?"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Areas for Improvement</label>
                                <textarea class="form-control" name="weaknesses" rows="2"
                                          placeholder="What areas need improvement?"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Recommendations <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="recommendations" rows="3" required
                                          placeholder="Provide specific recommendations for the student"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if (!status || row.querySelector('.badge').textContent.toLowerCase().includes(status.toLowerCase())) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>