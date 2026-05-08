# 🌳 Employee Tree Management System

A dynamic web application built with **Laravel** to visualize and manage organizational hierarchies through an interactive "Employee Tree." This project features a customizable 3D background system and a robust backend for employee data management.

---

## 🔗 Live Demo
You can try the live version of the project here:  
👉 **[Live Demo](http://omar-soft.free.nf/)**

---
---

## 🚀 Key Features

* **Dynamic Org Chart**: Visualizes employee relationships directly from the MySQL database using **BalkanGraph**.
* **Interactive 3D Themes**: Users can switch between multiple animated backgrounds (Birds, Net, Globe, etc.) via a custom UI.
* **Smart Background Management**: Implemented logic to safely destroy and re-initialize Vanta.js effects, preventing memory leaks and browser lag.
* **Custom Arabic Typography**: Integrated professional Arabic fonts like **Cairo** and **Aref Ruqaa** for a polished look.

---

## 🛠️ Tech Stack

* **Backend**: PHP / Laravel Framework.
* **Frontend**: JavaScript (ES6+), HTML5, CSS3.
* **Database**: MySQL.
* **Libraries & Tools**:
    * **Three.js**: Core 3D engine for rendering animations.
    * **Vanta.js**: Used for the animated 3D background effects.
    * **SweetAlert2**: Powers the interactive theme selection menu.
    * **BalkanGraph**: Used for the structural display of the employee tree.

---

## 🧠 Technical Challenges & Learning

During the development of this project, I tackled several advanced frontend and backend challenges:

1.  **Script Synchronization**: Resolved critical "THREE is not defined" errors by optimizing the loading order of external libraries in the HTML `<head>`.
2.  **DOM Node Lifecycle**: Fixed "removeChild" and "Constructor" errors by ensuring 3D scenes are properly cleaned up before new ones are rendered.
3.  **State Management**: Managed global instances of 3D effects to allow seamless switching between different themes without reloading the page.

---