import axios from "axios";
import {API} from "@/constants/app";

const api = axios.create({
    baseURL: `${API}`,
    timeout: 1000,
    headers: {
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Headers":
            "X-Requested-With, Content-Type, Accept, Origin, Authorization",
        "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
    },
});

export default api;
