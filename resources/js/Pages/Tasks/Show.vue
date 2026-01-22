<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  task: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Head title="Task Details" />

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
            <li class="breadcrumb-item active" aria-current="page">Details</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="mb-0">Task Details</h5>
              <div class="d-flex gap-2">
                <Link :href="route('tasks.edit', task)" class="btn btn-primary">
                  <i class="bi bi-pencil-square me-2"></i>Edit
                </Link>
                <Link :href="route('tasks.index')" class="btn btn-secondary">
                  Back to List
                </Link>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-12">
                <label class="form-label fw-bold">Task Description</label>
                <p class="mb-0">{{ task.task_description }}</p>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold">Assigned By</label>
                <p class="mb-0">
                  {{ task.assigned_by_employee?.full_name || "-" }}
                  <span v-if="task.assigned_by_employee">
                    ({{ task.assigned_by_employee.employee_id }})
                  </span>
                </p>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold">Assigned To</label>
                <p class="mb-0">
                  {{
                    task.assigned_to_user?.full_name ||
                    task.assigned_to_user?.name ||
                    "-"
                  }}
                  <span v-if="task.assigned_to_user">
                    ({{ task.assigned_to_user.email }})
                  </span>
                </p>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-bold">Time Spent</label>
                <p class="mb-0">
                  {{
                    task.time_spent_minutes
                      ? task.time_spent_minutes + " minutes"
                      : "-"
                  }}
                </p>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-bold">Started At</label>
                <p class="mb-0">
                  {{
                    task.started_at
                      ? new Date(task.started_at).toLocaleString()
                      : "-"
                  }}
                </p>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-bold">Completed At</label>
                <p class="mb-0">
                  {{
                    task.completed_at
                      ? new Date(task.completed_at).toLocaleString()
                      : "-"
                  }}
                </p>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-bold">Status</label>
                <p class="mb-0">
                  <span v-if="task.completed_at" class="badge bg-success"
                    >Completed</span
                  >
                  <span v-else class="badge bg-warning">Pending</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
