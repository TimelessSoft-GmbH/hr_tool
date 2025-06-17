import React, { useEffect, useState } from "react";
import api from "../utils/api";

const ProfileImage = ({ userId, name, size = 40 }) => {
  const [imageUrl, setImageUrl] = useState("");

  useEffect(() => {
    const fetchImage = async () => {
      try {
        const res = await api.get(`/users/${userId}/image`, {
          responseType: "blob",
          validateStatus: (status) => status === 200 || status === 204,
        });

        if (res.status === 200) {
          const url = URL.createObjectURL(res.data);
          setImageUrl(url);
        }
      } catch (err) {
        console.error("Failed to load user image", err);
      }
    };

    fetchImage();
  }, [userId]);

  return imageUrl ? (
    <img
      src={imageUrl}
      alt={`${name}'s avatar`}
      className="rounded-full object-cover"
      style={{ width: size, height: size }}
    />
  ) : (
    <div
      className="inline-flex items-center justify-center bg-gray-300 rounded-full"
      style={{ width: size, height: size }}
    >
      <span className="font-medium text-gray-600 text-sm">
        {name?.slice(0, 1).toUpperCase()}
      </span>
    </div>
  );
};

export default ProfileImage;
