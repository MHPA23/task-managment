import { defineStore } from "pinia";

export const useTaskStore = defineStore("task", {
    state: () => ({
        sortBy: "created_at",
        sortDir: "desc",
        completed: null,
    }),
    getters: {
        queryParams(state) {
            return {
                sort_by: state.sortBy,
                sort_dir: state.sortDir,
                ...(state.completed !== null && { completed: state.completed }),
            };
        },
    },
});
