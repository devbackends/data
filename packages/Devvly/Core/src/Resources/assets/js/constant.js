export const GOOGLE_MAPS_API_KEY = "AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs"

export const API_ENDPOINTS = {
    fluid: {
        getTokenizerinfo: "/fluid/api/get-tokenizer-info",
        card: {
            get:   "/fluid/api/card/{customerId}/{sellerId}",
            remove:"/fluid/api/card/{cardId}"
        }
    }
};