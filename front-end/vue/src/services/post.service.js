import axios from 'axios';

axios.defaults.baseURL = process.env.apiUrl;

const postService = {

  latest(limit = null, options = null) {

    limit = (limit) ? limit : 6;
    options = options ? options : {};

    return axios.get("posts/latest/" + limit)
  }
};

export default postService;
