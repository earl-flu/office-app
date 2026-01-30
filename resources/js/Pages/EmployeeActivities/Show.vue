<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  activity: {
    type: Object,
    required: true,
  },
});

const statusBadgeClass = (status) => {
  const map = {
    pending: "bg-warning text-dark",
    in_progress: "bg-info text-dark",
    finished: "bg-success",
    cancelled: "bg-secondary",
  };
  return map[status] ?? "bg-secondary";
};

const formatStatus = (status) => (status ? status.replace("_", " ") : "");

const formatMinutes = (minutes) => (minutes != null ? `${minutes} min` : "—");
</script>

<template>
  <Head :title="`Activity: ${activity.activity_type?.name ?? 'Details'}`" />

  <AuthenticatedLayout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Employee Activity</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <Link :href="route('employee-activities.index')">
                <i class="bx bx-home-alt"></i>
              </Link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">View</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-body p-4">
            <div class="d-flex align-items-start justify-content-between mb-4">
              <h5 class="mb-0">Activity Details</h5>
              <div class="d-flex gap-2">
                <Link
                  :href="route('employee-activities.edit', activity)"
                  class="btn btn-primary px-3"
                >
                  <i class="bi bi-pencil-square me-1"></i>Edit
                </Link>
                <Link
                  :href="route('employee-activities.index')"
                  class="btn btn-outline-secondary px-3"
                >
                  Back to List
                </Link>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Activity Type</label
                >
                <p class="mb-0 fw-medium">
                  {{ activity.activity_type?.name ?? "—" }}
                </p>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Status</label
                >
                <p class="mb-0">
                  <span
                    class="badge"
                    :class="statusBadgeClass(activity.status)"
                  >
                    {{ formatStatus(activity.status) }}
                  </span>
                </p>
              </div>

              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Activity Date</label
                >
                <p class="mb-0 fw-medium">
                  {{ activity.activity_date ?? "—" }}
                </p>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Time Spent</label
                >
                <p class="mb-0 fw-medium">
                  {{ formatMinutes(activity.time_spent_minutes) }}
                </p>
              </div>

              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Employee</label
                >
                <p class="mb-0 fw-medium">
                  {{ activity.employee?.full_name ?? "—" }}
                </p>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small text-uppercase"
                  >Assigned By</label
                >
                <p class="mb-0 fw-medium">
                  {{ activity.assigned_by?.full_name ?? "—" }}
                </p>
              </div>

              <div class="col-12">
                <label class="form-label text-muted small text-uppercase"
                  >Description</label
                >
                <p class="mb-0 text-break">
                  {{ activity.description || "—" }}
                </p>
              </div>

              <div v-if="activity.remarks" class="col-12">
                <label class="form-label text-muted small text-uppercase"
                  >Remarks</label
                >
                <p class="mb-0 text-break">
                  {{ activity.remarks }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
