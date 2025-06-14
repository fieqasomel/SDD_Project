<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Inquiry Details') }} - #{{ $complaint->inquiry->I_ID ?? 'N/A' }}
            </h2>
            <a href="{{ route('agency.inquiries.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                {{ __('Back to Inquiries') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Inquiry Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Inquiry Information') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Subject') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $complaint->inquiry->I_Subject ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $complaint->inquiry->I_Category ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Priority') }}</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $complaint->inquiry->I_Priority ?? 'Normal' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Submitted Date') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->inquiry->I_Date ? \Carbon\Carbon::parse($complaint->inquiry->I_Date)->format('Y-m-d H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Current Status') }}</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($complaint->C_Status == 'assigned') bg-yellow-100 text-yellow-800
                                    @elseif($complaint->C_Status == 'under_investigation') bg-blue-100 text-blue-800
                                    @elseif($complaint->C_Status == 'verified_true') bg-green-100 text-green-800
                                    @elseif($complaint->C_Status == 'identified_fake') bg-red-100 text-red-800
                                    @elseif($complaint->C_Status == 'completed') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->C_Status)) }}
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Assigned Date') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->C_AssignedDate ? \Carbon\Carbon::parse($complaint->C_AssignedDate)->format('Y-m-d H:i:s') : 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Last Updated') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->C_UpdatedDate ? \Carbon\Carbon::parse($complaint->C_UpdatedDate)->format('Y-m-d H:i:s') : 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Deadline') }}</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $complaint->C_Deadline ? \Carbon\Carbon::parse($complaint->C_Deadline)->format('Y-m-d') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-900">{{ $complaint->inquiry->I_Description ?? 'No description available' }}</p>
                        </div>
                    </div>
                    @if($complaint->C_Remarks)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">{{ __('Current Remarks') }}</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900">{{ $complaint->C_Remarks }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Update Section -->
            @if(in_array($complaint->C_Status, ['assigned', 'under_investigation', 'pending_review']))
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Update Status') }}</h3>
                    <form method="POST" action="{{ route('agency.inquiries.update-status', $complaint->C_ID) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('New Status') }}</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="under_investigation">{{ __('Under Investigation') }}</option>
                                    <option value="verified_true">{{ __('Verified as True') }}</option>
                                    <option value="identified_fake">{{ __('Identified as Fake') }}</option>
                                    <option value="completed">{{ __('Completed') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700">{{ __('Remarks') }}</label>
                                <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Add remarks about the status change..."></textarea>
                                @error('remarks')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Update Status') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Add Update Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Add Update') }}</h3>
                    <form method="POST" action="{{ route('agency.inquiries.add-update', $complaint->C_ID) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="update_text" class="block text-sm font-medium text-gray-700">{{ __('Update Details') }}</label>
                            <textarea name="update_text" id="update_text" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Add investigation updates, findings, or communications..." required></textarea>
                            @error('update_text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-700">{{ __('Internal Update (not visible to MCMC)') }}</span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                {{ __('Add Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status History Section -->
            @if($statusHistory && $statusHistory->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Status History') }}</h3>
                    <div class="space-y-4">
                        @foreach($statusHistory as $history)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ __('Status changed to:') }} 
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                        </span>
                                    </p>
                                    @if($history->remarks)
                                        <p class="mt-1 text-sm text-gray-600">{{ $history->remarks }}</p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($history->updated_at)->format('Y-m-d H:i:s') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Updates/Communications History -->
            @if($complaint->updates && $complaint->updates->count() > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Updates & Communications') }}</h3>
                    <div class="space-y-4">
                        @foreach($complaint->updates as $update)
                        <div class="border-l-4 {{ $update->is_internal ? 'border-yellow-500' : 'border-green-500' }} pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $update->is_internal ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $update->is_internal ? 'Internal' : 'Official' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-900">{{ $update->update_text }}</p>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($update->created_at)->format('Y-m-d H:i:s') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>