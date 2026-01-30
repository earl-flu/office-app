<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const props = defineProps({
  stats: {
    type: Object,
    required: true,
  },
  recentActivities: {
    type: Array,
    default: () => [],
  },
  weeklyStats: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  assignedTimeSeries: {
    type: Array,
    default: () => [],
  },
  typeTimeSeries: {
    type: Array,
    default: () => [],
  },
});

const dateFrom = ref(props.filters?.date_from || "");
const dateTo = ref(props.filters?.date_to || "");

watch([dateFrom, dateTo], () => {
  router.get(
    route("dashboard"),
    {
      date_from: dateFrom.value || undefined,
      date_to: dateTo.value || undefined,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
});

const formatMinutes = (minutes) => {
  if (!minutes) return "0 min";
  if (minutes < 60) return `${minutes} min`;
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return mins > 0 ? `${hours}h ${mins}min` : `${hours}h`;
};

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

const assignedTimeChartOptions = computed(() => ({
  chart: {
    id: "assigned-time-chart",
    toolbar: { show: false },
  },
  xaxis: {
    categories: props.assignedTimeSeries.map((item) => item.label),
    labels: {
      style: {
        colors: "#cdcdcd",
      },
    },
  },
  yaxis: {
    labels: {
      formatter: (value) => Math.round(value),
    },
  },
  dataLabels: {
    enabled: false,
  },
  tooltip: {
    theme: "dark",
    y: {
      formatter: (value) => `${Math.round(value)} min`,
    },
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "dark",
      gradientToColors: ["#009efd"],
      shadeIntensity: 1,
      type: "vertical",
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100],
    },
  },
  colors: ["#2af598"],
}));

const assignedTimeChartSeries = computed(() => [
  {
    name: "Time Spent (min)",
    data: props.assignedTimeSeries.map((item) => item.minutes),
  },
]);

const typeTimeChartOptions = computed(() => ({
  chart: {
    id: "type-time-chart",
    toolbar: { show: false },
  },
  xaxis: {
    categories: props.typeTimeSeries.map((item) => item.label),
    labels: {
      style: {
        colors: "#cdcdcd",
      },
    },
  },
  yaxis: {
    labels: {
      formatter: (value) => Math.round(value),
    },
  },
  dataLabels: {
    enabled: false,
  },
  tooltip: {
    theme: "dark",
    y: {
      formatter: (value) => `${Math.round(value)} min`,
    },
  },
  fill: {
    type: "gradient",
    gradient: {
      shade: "dark",
      gradientToColors: ["#009efd"],
      shadeIntensity: 1,
      type: "vertical",
      opacityFrom: 1,
      opacityTo: 1,
      stops: [0, 100],
    },
  },
  colors: ["#56CCF2"],
}));

const typeTimeChartSeries = computed(() => [
  {
    name: "Time Spent (min)",
    data: props.typeTimeSeries.map((item) => item.minutes),
  },
]);
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Dashboard</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Overview</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Date Range Filters -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" class="form-control" v-model="dateFrom" />
      </div>
      <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" class="form-control" v-model="dateTo" />
      </div>
      <div class="col-md-6 d-flex align-items-end">
        <p class="text-muted mb-0">
          Filtering your activities by <strong>Activity Date</strong>.
        </p>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card radius-10 border-start border-0 border-3 border-info">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">Total Activities</p>
                <h4 class="my-1 text-info">{{ stats.total }}</h4>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"
              >
                <i class="bi bi-list-task"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div
          class="card radius-10 border-start border-0 border-3 border-warning"
        >
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">Pending</p>
                <h4 class="my-1 text-warning">{{ stats.pending }}</h4>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"
              >
                <i class="bi bi-clock-history"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div
          class="card radius-10 border-start border-0 border-3 border-primary"
        >
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">In Progress</p>
                <h4 class="my-1 text-primary">{{ stats.in_progress }}</h4>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"
              >
                <i class="bi bi-arrow-repeat"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div
          class="card radius-10 border-start border-0 border-3 border-success"
        >
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">Finished</p>
                <h4 class="my-1 text-success">{{ stats.finished }}</h4>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"
              >
                <i class="bi bi-check-circle"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-6">
        <div class="card radius-10">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">Total Time Spent</p>
                <h4 class="my-1">
                  {{ formatMinutes(stats.total_time_minutes) }}
                </h4>
                <p class="mb-0 font-13">
                  <span class="text-success"><i class="bi bi-clock"></i> </span>
                </p>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"
              >
                <i class="bi bi-stopwatch"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="card radius-10">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div>
                <p class="mb-0 text-secondary">This Week</p>
                <h4 class="my-1">{{ weeklyStats.total }} activities</h4>
                <p class="mb-0 font-13">
                  <span class="text-success"
                    >{{ weeklyStats.finished }} finished</span
                  >
                </p>
              </div>
              <div
                class="widgets-icons-2 rounded-circle bg-gradient-moonlit text-white ms-auto"
              >
                <i class="bi bi-calendar-week"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Time Spent Charts -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-xl-6 d-flex align-items-stretch">
        <div class="card w-100 rounded-4">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <h6 class="mb-0">Time Spent by Assigned By</h6>
            </div>
            <div v-if="assignedTimeSeries.length === 0" class="text-muted py-4">
              No time tracking data available for this period.
            </div>
            <apexchart
              v-else
              class="mt-3"
              type="bar"
              :options="assignedTimeChartOptions"
              :series="assignedTimeChartSeries"
            ></apexchart>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6 d-flex align-items-stretch">
        <div class="card w-100 rounded-4">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <h6 class="mb-0">Time Spent by Activity Type</h6>
            </div>
            <div v-if="typeTimeSeries.length === 0" class="text-muted py-4">
              No time tracking data available for this period.
            </div>
            <apexchart
              v-else
              class="mt-3"
              type="bar"
              :options="typeTimeChartOptions"
              :series="typeTimeChartSeries"
            ></apexchart>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
              <Link
                :href="route('employee-activities.create')"
                class="btn btn-primary px-4"
              >
                <i class="bi bi-plus-lg me-2"></i>Create Activity
              </Link>
              <Link
                :href="route('employee-activities.index')"
                class="btn btn-outline-primary px-4"
              >
                <i class="bi bi-list-ul me-2"></i>View All Activities
              </Link>
              <Link
                :href="route('profile.edit')"
                class="btn btn-outline-secondary px-4"
              >
                <i class="bi bi-person me-2"></i>Edit Profile
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="mb-0">Recent Activities</h5>
              <Link
                :href="route('employee-activities.index')"
                class="btn btn-sm btn-outline-primary"
              >
                View All
              </Link>
            </div>

            <div v-if="recentActivities.length === 0" class="text-center py-4">
              <p class="text-muted mb-0">No activities yet.</p>
              <Link
                :href="route('employee-activities.create')"
                class="btn btn-sm btn-primary mt-2"
              >
                Create Your First Activity
              </Link>
            </div>

            <div v-else class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Assigned By</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="activity in recentActivities" :key="activity.id">
                    <td>{{ activity.activity_date }}</td>
                    <td>{{ activity.activity_type?.name ?? "—" }}</td>
                    <td>
                      {{
                        activity.description.length > 50
                          ? activity.description.slice(0, 50) + "..."
                          : activity.description
                      }}
                    </td>
                    <td>{{ activity.assigned_by?.full_name ?? "—" }}</td>
                    <td>
                      {{
                        activity.time_spent_minutes
                          ? formatMinutes(activity.time_spent_minutes)
                          : "—"
                      }}
                    </td>
                    <td>
                      <span
                        class="badge"
                        :class="statusBadgeClass(activity.status)"
                      >
                        {{ formatStatus(activity.status) }}
                      </span>
                    </td>
                    <td>
                      <Link
                        :href="route('employee-activities.show', activity)"
                        class="btn btn-sm btn-info"
                        title="View"
                      >
                        <i class="bi bi-eye"></i>
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
