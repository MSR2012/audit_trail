import React, {useState} from 'react';
import {Outlet} from 'react-router-dom';
import LoadingSpinner from '../components/LoadingSpinner';
import Navbar from '../components/Navbar';

const Main = () => {

    const [loading, setLoading] = useState(false)

    return (
        <div className="bg-white h-full">
            {loading ? (
                <LoadingSpinner/>
            ) : (
                <div>
                    <Navbar/>
                    <div className="min-h-screen">
                        <Outlet/>
                    </div>
                </div>
            )}
        </div>
    )
}

export default Main