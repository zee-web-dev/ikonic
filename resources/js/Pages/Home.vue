<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { onMounted, reactive } from 'vue'
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Spinner from '@/Components/Spinner.vue';

const state = reactive({
    data: [],
    commondata: [],
    count: [],
    currentSection: '',
    paginate: 10,
    totalResult: 0,
});

const activeClass = 'inline-block w-full p-4 text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white';
const inActiveClass = 'inline-block w-full p-4 bg-white hover:text-gray-700 hover:bg-gray-50  dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700';
const btnPrimary = "px-4 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white  uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-blue-800 active:bg-blue-900 transition ease-in-out duration-150";
const btnDanger = "inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition ease-in-out duration-150";

async function count() {
    await axios.get('../user/count').then((data) => {
        state.count = data.data;
        // console.log(data);

    });
}

// Get suggestions
async function getSuggestions(section, paginate) {
    console.log(paginate);
    state.currentSection = section;
    await axios.get(`../user/suggestions?paginate=${paginate}`).then((data) => {
        state.data = data.data.data;
        state.paginate = data.data.per_page;
        state.totalResult = data.data.total;
        // console.log(state.paginate);

    });
}

async function getSentReqs(section) {
    state.currentSection = section;
    await axios.get('../user/sent-requests').then((data) => {
        state.data = data.data.data;
        // console.log(data);

    });
}

async function getRecReqs(section) {
    state.currentSection = section;
    await axios.get('../user/received-requests').then((data) => {
        state.data = data.data.data;
        // console.log(data);

    });
}

async function getConnections(section) {
    state.currentSection = section;
    await axios.get('../user/connections').then((data) => {
        state.data = data.data.data;
        // console.log(data);

    });
}

const sendConnectionRequest = async (id) => {
    await axios.get(`../user/send-request/${id}`).then((data) => {
        count();
        getSuggestions('suggestions', 10);
        console.log(data);

    });
}

const withdrawRequest = async (id) => {
    console.log(id);
    await axios.get(`../user/withdraw-request/${id}`).then((data) => {
        count();
        getSentReqs('sent-req');
        console.log(data);

    });
}

const acceptRequest = async (id) => {
    console.log(id);
    await axios.get(`../user/accept-request/${id}`).then((data) => {
        count();
        getRecReqs('recieved-req');
        console.log(data);

    });
}

const removeConnection = async (id) => {
    await axios.get(`../user/remove-connection/${id}`).then((data) => {
        count();
        getConnections('connections');

    });
}

const commonConnection = async (id) => {
    await axios.get(`../user/common-connections/${id}`).then((data) => {
        state.commondata = data.data.data;

    });
}



onMounted(() => {
    count();
    getSuggestions('suggestions', state.paginate);
})

</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Home</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400"
                        id="myTab">
                        <li class="w-full">
                            <button @click="getSuggestions('suggestions', state.paginate)"
                                :class="[state.currentSection === 'suggestions' ? activeClass : inActiveClass]">
                                Suggestions ({{ state.count.total_suggestions }})</button>
                        </li>
                        <li class="w-full">
                            <button @click="getSentReqs('sent-req')"
                                :class="[state.currentSection === 'sent-req' ? activeClass : inActiveClass]">
                                Sent Requests ({{ state.count.total_sent_req }})</button>
                        </li>
                        <li class="w-full">
                            <button @click="getRecReqs('recieved-req')"
                                :class="[state.currentSection === 'recieved-req' ? activeClass : inActiveClass]">
                                Recieved Requests ({{ state.count.total_rec_req }})</button>
                        </li>
                        <li class="w-full">
                            <button @click="getConnections('connections')"
                                :class="[state.currentSection === 'connections' ? activeClass : inActiveClass]">
                                Connections ({{ state.count.total_connections }})</button>
                        </li>
                    </ul>
                </div>


                <div v-if="state.currentSection === 'suggestions'" class="bg-white dark:bg-gray-800 p-8 rounded shadow">
                    <div v-if="state.data.length > 0" v-for="(user, i) in state.data" class="dark:text-white px-4 py-2">
                        <Spinner v-if="!user.name" />
                        <div v-if="user.name" class="flex justify-between">
                            <h1>{{ user.name }} - {{ user.email }}</h1>
                            <button @click="sendConnectionRequest(user.id)" :class="btnPrimary">Connect</button>
                        </div>
                    </div>
                    <div v-if="state.totalResult > 10 && state.totalResult  > state.paginate + 9" class="flex justify-center">
                        <button @click="getSuggestions('suggestions', state.paginate + 10)" :class="btnPrimary">Load
                            More</button>
                    </div>
                </div>

                <div v-if="state.currentSection === 'sent-req'" class="bg-white dark:bg-gray-800 p-8 rounded shadow">
                    <div v-if="state.data.length > 0" v-for="(user, i) in state.data" class="dark:text-white px-4 py-2">
                        <Spinner v-if="!user.receiver" />
                        <div v-if="user.receiver" class="flex justify-between">
                            <h1>{{ user.receiver?.name }} - {{ user.receiver?.email }}</h1>
                            <button @click="withdrawRequest(user.receiver?.id)" :class="btnDanger">Withdraw Request</button>
                        </div>
                    </div>
                </div>

                <div v-if="state.currentSection === 'recieved-req'" class="bg-white dark:bg-gray-800 p-8 rounded shadow">
                    <div v-if="state.data.length > 0" v-for="(user, i) in state.data" class="dark:text-white px-4 py-2">
                        <Spinner v-if="!user.sender" />
                        <div v-if="user.sender" class="flex justify-between">
                            <h1>{{ user.sender?.name }} - {{ user.sender?.email }}</h1>
                            <button @click="acceptRequest(user.id)" :class="btnPrimary">Accept</button>
                        </div>
                    </div>
                </div>

                <div v-if="state.currentSection === 'connections'" class="bg-white dark:bg-gray-800 p-8 rounded shadow">
                    <Spinner v-if="state.data.length < 1" />
                    <div v-if="state.data.length > 0" v-for="(user, i) in state.data" class="dark:text-white px-4 py-2">
                        <Spinner v-if="!user.name" />
                        <div v-if="user.name" class="flex justify-between">
                            <h1>{{ user.name }} - {{ user.email }}</h1>
                            <div>
                                <button :class="btnPrimary" class="mr-3" @click="commonConnection(user.id)">Common
                                    Connection</button>
                                <button :class="btnDanger" @click="removeConnection(user.id)">Remove Connection</button>
                            </div>
                        </div>


                    </div>

                    <div v-if="state.commondata.length > 0" v-for="(common, i) in state.commondata"
                        class="dark:text-white px-4 py-2">
                        <Spinner v-if="!common.name" />
                        <div v-if="common.name" class="flex justify-between">
                            <h1>{{ common.name }} - {{ common.email }}</h1>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
