import React, { useState, useEffect } from "react";
import axios from "axios";

const UpdateProfileImage = () => {
  const [image, setImage] = useState(null);
  const [previewUrl, setPreviewUrl] = useState("");
  const [status, setStatus] = useState("");
  const [error, setError] = useState("");

  axios.defaults.withCredentials = true;

  useEffect(() => {
    axios
      .get("/api/user", { withCredentials: true })
      .then((res) => {
        if (res.data.image && res.data.image !== "basicUser.png") {
          setPreviewUrl(`/images/${res.data.image}`);
        }
      });
  }, []);

  const handleChange = (e) => {
    const file = e.target.files[0];
    setImage(file);
    if (file) {
      setPreviewUrl(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("");
    setError("");

    if (!image) {
      setError("Please select an image to upload.");
      return;
    }

    const formData = new FormData();
    formData.append("image", image);

    try {
      await axios.post("/api/profile", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      setStatus("Profile image updated successfully.");
    } catch (err) {
      setError(
        err.response?.data?.message || "An error occurred while uploading."
      );
    }
  };

  return (
    <div>
      <h2 className="text-lg font-medium text-gray-900 pb-1">
        Update Profile Image
      </h2>
      <p className="text-sm text-gray-600 pb-4">
        Upload an image of your choosing.
      </p>

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        <input
          type="file"
          accept="image/*"
          name="image"
          onChange={handleChange}
          className="mb-4"
        />

        {previewUrl && (
          <img
            src={previewUrl}
            alt="Preview"
            style={{ width: "80px", marginTop: "10px" }}
          />
        )}

        {error && <p className="text-red-500 text-sm mt-2">{error}</p>}
        {status && <p className="text-green-600 text-sm mt-2">{status}</p>}

        <button
          type="submit"
          className="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
        >
          Upload Profile
        </button>
      </form>
    </div>
  );
};

export default UpdateProfileImage;
