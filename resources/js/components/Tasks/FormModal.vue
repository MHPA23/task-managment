<script setup>
import { ref, reactive, defineProps, defineEmits, watch } from 'vue'
import { useToast } from 'vue-toastification'

const props = defineProps({
    show: Boolean,
    editingTask: {
        type: Object,
        default: null,
    },
    categories: Array,
})

const emit = defineEmits(['close', 'save'])
const toast = useToast()
const errors = ref({})

const form = reactive({
    title: '',
    description: '',
    completed: false,
    due_date: '',
})

// Reset form when modal opens/closes or editing task changes
const resetForm = () => {
    if (props.editingTask) {
        form.title = props.editingTask.title
        form.description = props.editingTask.description
        form.completed = props.editingTask.completed
        form.due_date = props.editingTask.due_date || ''
        form.category_id = props.editingTask.category_id
    } else {
        form.title = ''
        form.description = ''
        form.completed = false
        form.due_date = ''
    }
    errors.value = {}
}

const handleSubmit = async () => {
    try {
        await emit('save', form)
        emit('close')
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors
        } else {
            toast.error(error.response?.data?.error || 'An error occurred')
        }
    }
}

// Watch for changes in show prop to reset form
watch(
    () => props.show,
    (newVal) => {
        if (newVal) resetForm()
    },
)

// Watch for changes in editingTask to update form
watch(
    () => props.editingTask,
    () => resetForm(),
    { immediate: true },
)
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">
                {{ editingTask ? 'Edit Task' : 'New Task' }}
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Category</label
                    >
                    <select
                        v-model="form.category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.category_id }"
                    >
                        <option value="" disabled selected>
                            Select a category
                        </option>
                        <option
                            v-for="category in props.categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <p
                        v-if="errors.category_id"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.category_id[0] }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Title</label
                    >
                    <input
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.title }"
                    />
                    <p v-if="errors.title" class="mt-1 text-sm text-red-600">
                        {{ errors.title[0] }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.description }"
                        rows="3"
                    ></textarea>
                    <p
                        v-if="errors.description"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.description[0] }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Due Date</label
                    >
                    <input
                        v-model="form.due_date"
                        type="datetime-local"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        :class="{ 'border-red-500': errors.due_date }"
                    />
                    <p v-if="errors.due_date" class="mt-1 text-sm text-red-600">
                        {{ errors.due_date[0] }}
                    </p>
                </div>

                <div class="flex items-center">
                    <input
                        v-model="form.completed"
                        type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-2 block text-sm text-gray-900"
                        >Task Completed</label
                    >
                </div>

                <div class="flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                    >
                        {{ editingTask ? 'Save' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
