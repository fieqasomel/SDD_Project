@extends('layouts.app')

@section('title', 'Assign Inquiry')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap4 .select2-selection {
        border-color: #d1d5db !important;
    }
    .select2-container--bootstrap4 .select2-selection:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    }
    .agency-card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Assign Inquiry to Agency</h3>
            <a href="{{ route('assignments.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600">
                <i class="fas fa-arrow-left mr-1"></i> Back to Assignments
            </a>
        </div>

        <div class="p-6">
            <!-- Inquiry Details -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                <h4 class="text-lg font-semibold text-blue-700 mb-2">Inquiry Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p><strong>Inquiry ID:</strong> {{ $inquiry->I_ID }}</p>
                        <p><strong>Title:</strong> {{ $inquiry->I_Title }}</p>
                        <p><strong>Category:</strong> <span class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $inquiry->I_Category }}</span></p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded bg-{{ $inquiry->getStatusBadgeColor() === 'success' ? 'green' : ($inquiry->getStatusBadgeColor() === 'danger' ? 'red' : 'yellow') }}-500 text-white">{{ $inquiry->I_Status }}</span></p>
                        <p><strong>Date:</strong> {{ $inquiry->I_Date ? \Carbon\Carbon::parse($inquiry->I_Date)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p><strong>Submitted by:</strong> {{ $inquiry->publicUser->PU_Name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $inquiry->publicUser->PU_Email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $inquiry->publicUser->PU_PhoneNum ?? 'N/A' }}</p>
                        <p><strong>Source:</strong> {{ $inquiry->I_Source ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <strong>Description:</strong>
                    <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $inquiry->I_Description }}</p>
                </div>
            </div>

            <!-- Available Agencies Section -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3">
                    <i class="fas fa-building mr-2 text-indigo-600"></i>Available Registered Agencies
                </h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    @if($agencies->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            @foreach($agencies as $agency)
                                <div class="agency-card bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-800 text-sm">{{ $agency->A_Name }}</h5>
                                            <p class="text-xs text-gray-600 mt-1">
                                                <i class="fas fa-tag mr-1"></i>{{ is_array($agency->A_Category) ? implode(', ', $agency->A_Category) : $agency->A_Category }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-envelope mr-1"></i>{{ $agency->A_Email }}
                                            </p>
                                            @if($agency->A_PhoneNum)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-phone mr-1"></i>{{ $agency->A_PhoneNum }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded">
                            <div class="flex">
                                <i class="fas fa-info-circle text-green-600 mt-1 mr-2"></i>
                                <div>
                                    <p class="text-sm text-green-800">
                                        <strong>{{ $agencies->count() }}</strong> registered 
                                        {{ $agencies->count() == 1 ? 'agency is' : 'agencies are' }} 
                                        available. All agencies can now accept inquiries from any category, including "<strong>{{ $inquiry->I_Category }}</strong>".
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
                            <h5 class="text-lg font-semibold text-gray-800 mb-2">No Agencies Available</h5>
                            <p class="text-gray-600">No registered agencies found for the "<strong>{{ $inquiry->I_Category }}</strong>" category.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assignment Form -->
            <form action="{{ route('assignments.store', $inquiry->I_ID) }}" method="POST" class="space-y-6" id="assignmentForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label for="agency_id" class="block font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-1 text-indigo-600"></i>Select Agency for Assignment 
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="agency_id" id="agency_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('agency_id') border-red-500 @enderror" required>
                                <option value="">-- Select Agency --</option>
                                @foreach($agencies as $agency)
                                    <option value="{{ $agency->A_ID }}" 
                                            data-name="{{ $agency->A_Name }}"
                                            data-category="{{ is_array($agency->A_Category) ? implode(', ', $agency->A_Category) : $agency->A_Category }}"
                                            data-email="{{ $agency->A_Email }}"
                                            {{ old('agency_id') == $agency->A_ID ? 'selected' : '' }}>
                                        {{ $agency->A_Name }} - {{ is_array($agency->A_Category) ? implode(', ', $agency->A_Category) : $agency->A_Category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agency_id')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-filter mr-1"></i>Only agencies that handle "<strong>{{ $inquiry->I_Category }}</strong>" category are shown.
                            </p>
                        </div>
                        
                        <!-- Selected Agency Preview -->
                        <div id="selectedAgencyPreview" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-semibold text-blue-800 mb-2">
                                <i class="fas fa-eye mr-1"></i>Selected Agency Details
                            </h5>
                            <div id="agencyDetails" class="text-sm text-blue-700">
                                <!-- Agency details will be populated here -->
                            </div>
                        </div>
                        <div>
                            <label for="comment" class="block font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-alt mr-1 text-indigo-600"></i>Assignment Instructions & Comments
                            </label>
                            <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('comment') border-red-500 @enderror" placeholder="Add any specific instructions, comments, or notes for the assigned agency regarding this inquiry...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-lightbulb mr-1"></i>
                                <strong>Tip:</strong> Provide clear instructions to help the agency understand the urgency, specific requirements, or any special considerations for this inquiry.
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
                            <h5 class="text-yellow-800 font-semibold mb-3">
                                <i class="fas fa-clipboard-list mr-2"></i>Assignment Information
                            </h5>
                            <div class="space-y-2 text-sm">
                                <p class="flex items-center">
                                    <i class="fas fa-calendar-alt w-4 text-yellow-600 mr-2"></i>
                                    <strong>Assignment Date:</strong> <span class="ml-1">{{ date('d M Y, H:i') }}</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-user-tie w-4 text-yellow-600 mr-2"></i>
                                    <strong>Assigned by:</strong> <span class="ml-1">{{ Auth::user()->M_Name }} (MCMC)</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-filter w-4 text-yellow-600 mr-2"></i>
                                    <strong>Category Match:</strong> <span class="ml-1">Verified</span>
                                </p>
                                <p class="flex items-center">
                                    <i class="fas fa-building w-4 text-yellow-600 mr-2"></i>
                                    <strong>Available Agencies:</strong> <span class="ml-1">{{ $agencies->count() }}</span>
                                </p>
                            </div>
                            
                            <div class="mt-4 space-y-2">
                                <div class="text-sm text-blue-800 bg-blue-100 p-2 rounded">
                                    <i class="fas fa-info-circle mr-1"></i> The agency will receive this inquiry for <strong>verification</strong> first.
                                </div>
                                <div class="text-sm text-green-800 bg-green-100 p-2 rounded">
                                    <i class="fas fa-check-circle mr-1"></i> Status will update to <strong>"In Progress"</strong> after agency acceptance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center space-x-4">
                            <button type="submit" id="assignButton" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 text-white text-sm font-medium px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-paper-plane mr-2"></i> 
                                <span id="assignButtonText">Assign Inquiry to Agency</span>
                            </button>
                            <a href="{{ route('assignments.index') }}" class="inline-flex items-center bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 text-gray-800 text-sm font-medium px-6 py-3 rounded-lg shadow hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Cancel & Return
                            </a>
                        </div>
                        
                        <div class="text-right text-sm text-gray-500">
                            <p class="flex items-center">
                                <i class="fas fa-shield-alt mr-1 text-green-500"></i>
                                This action will be logged for audit purposes
                            </p>
                        </div>
                    </div>
                    
                    <!-- Assignment Confirmation -->
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4" id="confirmationNote">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-2"></i>
                            <div class="text-sm text-red-700">
                                <p class="font-semibold">Please confirm before proceeding:</p>
                                <ul class="mt-2 list-disc list-inside space-y-1">
                                    <li>You have selected the appropriate agency for this inquiry</li>
                                    <li>The agency category matches the inquiry requirements</li>
                                    <li>Assignment instructions are clear and complete</li>
                                    <li>This action cannot be undone (reassignment will be required if needed)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- SweetAlert2 for better confirmation dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Initialize Select2 for agency selection
        $('#agency_id').select2({
            theme: 'bootstrap4',
            placeholder: '-- Select Agency --',
            allowClear: true
        });

        // Handle agency selection change
        $('#agency_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const agencyId = selectedOption.val();
            
            if (agencyId) {
                const agencyName = selectedOption.data('name');
                const agencyCategory = selectedOption.data('category');
                const agencyEmail = selectedOption.data('email');
                
                // Show agency preview
                $('#selectedAgencyPreview').removeClass('hidden');
                $('#agencyDetails').html(`
                    <div class="space-y-2">
                        <p><i class="fas fa-building mr-2"></i><strong>Agency:</strong> ${agencyName}</p>
                        <p><i class="fas fa-tag mr-2"></i><strong>Category:</strong> ${agencyCategory}</p>
                        <p><i class="fas fa-envelope mr-2"></i><strong>Email:</strong> ${agencyEmail}</p>
                    </div>
                `);
                
                // Update button text
                $('#assignButtonText').text(`Assign to ${agencyName}`);
                $('#assignButton').removeClass('bg-indigo-600 hover:bg-indigo-700').addClass('bg-green-600 hover:bg-green-700');
            } else {
                // Hide agency preview
                $('#selectedAgencyPreview').addClass('hidden');
                $('#assignButtonText').text('Assign Inquiry to Agency');
                $('#assignButton').removeClass('bg-green-600 hover:bg-green-700').addClass('bg-indigo-600 hover:bg-indigo-700');
            }
        });

        // Form submission confirmation
        $('#assignmentForm').on('submit', function(e) {
            const selectedAgency = $('#agency_id option:selected');
            
            if (!selectedAgency.val()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Agency Selected',
                    text: 'Please select an agency before proceeding with the assignment.',
                    confirmButtonColor: '#6366f1'
                });
                return false;
            }

            e.preventDefault();
            
            Swal.fire({
                title: 'Confirm Assignment',
                html: `
                    <div class="text-left">
                        <p class="mb-3">You are about to assign this inquiry to:</p>
                        <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                            <p><strong>Agency:</strong> ${selectedAgency.data('name')}</p>
                            <p><strong>Category:</strong> ${selectedAgency.data('category')}</p>
                            <p><strong>Email:</strong> ${selectedAgency.data('email')}</p>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">The agency will receive this inquiry for verification. This action will be logged for audit purposes.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-check mr-1"></i> Yes, Assign Now',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancel',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    $('#assignButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
                    
                    // Submit the form
                    this.submit();
                }
            });
        });

        // Add visual feedback for form validation
        $('input, select, textarea').on('blur', function() {
            if ($(this).is(':invalid')) {
                $(this).addClass('border-red-300 focus:border-red-500 focus:ring-red-500');
            } else {
                $(this).removeClass('border-red-300 focus:border-red-500 focus:ring-red-500');
            }
        });
    });
</script>
@endsection
