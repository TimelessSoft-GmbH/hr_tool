import React, { useState, useEffect } from "react";
import api from "../utils/api";

const UpdateProfileImage = () => {
  const [image, setImage] = useState(null);
  const [previewUrl, setPreviewUrl] = useState("");
  const [status, setStatus] = useState("");
  const [error, setError] = useState("");

  useEffect(() => {
    api
      .get("/users/me/image", {
        responseType: "blob",
        validateStatus: (status) => status === 200 || status === 204,
      })
      .then((res) => {
        if (res.status === 200) {
          const url = URL.createObjectURL(res.data);
          setPreviewUrl(url);
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

    try {
      const arrayBuffer = await image.arrayBuffer();

      await api.put("/users/me/image", arrayBuffer, {
        headers: {
          "Content-Type": image.type,
        },
      });

      setStatus("Profile image updated successfully.");
    } catch (err) {
      console.error(err);
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

      <form onSubmit={handleSubmit}>
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
