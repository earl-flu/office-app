<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
  employees: {
    type: Array,
    default: () => [],
  },
  users: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  task_description: "",
  assigned_by_employee_id: "",
  assigned_to_user_id: "",
  time_spent_minutes: "",
  started_at: "",
  completed_at: "",
});

const submit = () => {
  form.post(route("tasks.store"), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Task Created Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Create Task" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Task</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Add Task Form</h5>
            <form class="row g-3" @submit.prevent="submit">
              <div class="col-md-12">
                <label for="task_description" class="form-label"
                  >Task Description *</label
                >
                <textarea
                  class="form-control"
                  v-model="form.task_description"
                  id="task_description"
                  rows="4"
                  required
                ></textarea>
                <div class="invalid-feedback d-block">
                  {{ form.errors.task_description }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="assigned_by_employee_id" class="form-label"
                  >Assigned By (Employee)</label
                >
                <select
                  class="form-select"
                  v-model="form.assigned_by_employee_id"
                  id="assigned_by_employee_id"
                >
                  <option value="">Select Employee</option>
                  <option
                    v-for="employee in employees"
                    :key="employee.id"
                    :value="employee.id"
                  >
                    {{ employee.employee_id }} - {{ employee.full_name }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.assigned_by_employee_id }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="assigned_to_user_id" class="form-label"
                  >Assigned To (User) *</label
                >
                <select
                  class="form-select"
                  v-model="form.assigned_to_user_id"
                  id="assigned_to_user_id"
                  required
                >
                  <option value="">Select User</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.full_name || user.name }} ({{ user.email }})
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.assigned_to_user_id }}
                </div>
              </div>

              <div class="col-md-4">
                <label for="time_spent_minutes" class="form-label"
                  >Time Spent (Minutes)</label
                >
                <input
                  type="number"
                  class="form-control"
                  v-model="form.time_spent_minutes"
                  id="time_spent_minutes"
                  min="0"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.time_spent_minutes }}
                </div>
              </div>

              <div class="col-md-4">
                <label for="started_at" class="form-label">Started At</label>
                <input
                  type="datetime-local"
                  class="form-control"
                  v-model="form.started_at"
                  id="started_at"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.started_at }}
                </div>
              </div>

              <div class="col-md-4">
                <label for="completed_at" class="form-label"
                  >Completed At</label
                >
                <input
                  type="datetime-local"
                  class="form-control"
                  v-model="form.completed_at"
                  id="completed_at"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.completed_at }}
                </div>
              </div>

              <div class="col-md-12 mt-4">
                <div class="d-md-flex d-grid align-items-center gap-3">
                  <button
                    class="btn btn-grd btn-grd-primary px-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Save
                  </button>
                  <Link
                    :href="route('tasks.index')"
                    class="btn btn-secondary px-4"
                  >
                    Cancel
                  </Link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
