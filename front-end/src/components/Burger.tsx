import React, { useState } from "react";
import { motion, AnimatePresence } from 'motion/react';

export default function Burger({className = "", footElements, children}: {className?: string, footElements?: React.ReactNode, children: React.ReactNode}) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <nav className={`burger ${className}`} aria-label="Menu de navigation">
            <button onClick={() => setIsOpen(prev => !prev)} className="burger-btn">
                <img src="/images/burger.svg" alt="Ouvrir menu"/>
            </button>

            <AnimatePresence>
                {isOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: -10 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -10 }}
                        transition={{ duration: 0.2 }}
                        className="burger-menu"
                        aria-expanded="true"
                    >
                        <div className="burger-head">
                            <button onClick={() => setIsOpen(prev => !prev)} className="burger-btn">
                                <img src="/images/xmark.svg" alt="Fermer menu"/>
                            </button>
                            {children}
                        </div>
                        <div className="burger-foot">
                            {footElements}
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </nav>
    );
}