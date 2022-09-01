import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
// import * as Cookies from 'js-cookie';

Vue.use(Vuex);

const state = {
    token: '' || localStorage.getItem('app_user_token'),
    refreshToken: '' || localStorage.getItem('app_user_refresh_token'),
    user: '' || localStorage.getItem('user'),
    name: '' || localStorage.getItem('name')
};

const actions = {
    token(context, token) {
        context.commit('tokenizer', token);
    },

    refreshToken(context, refreshToken) {
        context.commit('refreshtokenizer', refreshToken);
    },

    invoker(context, user) {
        context.commit('invokeUser', user);
    },

    nameInvoker(context, name) {
        context.commit('invokeName', name);
    },

    clearState({ commit }) {
        commit('resetState');
    }
};

const mutations = {
    tokenizer (state, token) {
        state.token = token;
    },
    refreshtokenizer (state, refreshtoken) {
        state.refreshtoken = refreshtoken;
    },
    invokeUser(state, user) {
        state.user = user;
    },
    invokeName(state, name) {
        state.name = name;
    },
    resetState(state) {
        Object.assign(state, {});
    }
}

const store = new Vuex.Store({
    plugins: [createPersistedState({
        storage: window.localStorage
    })],
    state,
    getters: {
        token: (state) => {
            return state.token;
        },
        refreshToken: (state) => {
            return state.refreshToken;
        },
        user: (state) => {
            return state.user;
        },
        name: (state) => {
            return state.name;
        }
    },
    actions,
    mutations
})

export default store;