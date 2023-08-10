import axios from 'axios';

const API_BASE_URL = '..'; // Replace with your actual API base URL

// Example API calls using Axios

// Get suggestions
async function getSuggestions() {
    await axios.get('../user/suggestions').then((data) => {
        return data
        //         state.data = data.data.data;
        //         // console.log(data);
        //         // console.log(data.data);

    });
}

// Send connection request
async function sendConnectionRequest(targetUserId) {
    try {
        const response = await axios.post(`${API_BASE_URL}/user/send-connection-request/${targetUserId}`);
        return response.data;
    } catch (error) {
        console.error('Error sending connection request:', error);
        throw error;
    }
}

// Withdraw connection request
async function withdrawConnectionRequest(targetUserId) {
    try {
        const response = await axios.delete(`${API_BASE_URL}/user/withdraw-connection-request/${targetUserId}`);
        return response.data;
    } catch (error) {
        console.error('Error withdrawing connection request:', error);
        throw error;
    }
}

// ... Define more Axios calls for other UserController methods

export {
    getSuggestions,
    sendConnectionRequest,
    withdrawConnectionRequest,
    // ... Export other Axios calls
};
