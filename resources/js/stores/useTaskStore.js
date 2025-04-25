import { defineStore } from "pinia";

export const useTaskStore = defineStore("task", {
    state: () => ({
        sortBy: "created_at",
        sortDir: "desc",
        completed: null,
        title: "",
        currentPage: 1,
        tasksUpdated: 0,
    }),

    getters: {
        queryParams: (state) => ({
            sort_by: state.sortBy,
            sort_dir: state.sortDir,
            completed: state.completed,
            title: state.title,
            page: state.currentPage,
        }),
    },

    actions: {
        notifyTaskUpdate() {
            this.tasksUpdated++;
        },
    },
});
